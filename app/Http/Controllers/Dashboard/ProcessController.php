<?php

namespace Gcr\Http\Controllers\Dashboard;

use Exception;
use Gcr\Address;
use Gcr\Company;
use Gcr\Cnae;
use Gcr\Document;
use Gcr\Events\FinishProcess;
use Gcr\Http\Controllers\Controller;
use Gcr\Owner;
use Gcr\Process;
use Gcr\Status;
use Gcr\Viability;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProcessController extends Controller
{
    /**
     * @var Process
     */
    private $process;

    /**
     * @var array
     */
    private $validationErrors = [];

    public function __construct(Process $process)
    {
        $this->authorizeResource(Process::class, 'process');
        $this->process = $process;
    }

    /**
     * @param int $typeCompany
     * @return mixed
     */
    private function getTitleByTypeCompany($typeCompany)
    {
        $typesCompany = Process::attributeOptions('type_company');
        return array_get($typesCompany, $typeCompany, 'Processo');
    }

    /**
     * @param int $typeCompany
     * @return mixed
     */
    private function getOwnersLabelByTypeCompany($typeCompany)
    {
        $ownersLabel = [
            Process::TYPE_COMPANY_BUSINESSMAN => 'Empresários',
            Process::TYPE_COMPANY_SOCIETY => 'Sócios',
            Process::TYPE_COMPANY_EIRELI => 'Integrantes',
            Process::TYPE_COMPANY_OTHER => 'Empresários',
        ];

        return array_get($ownersLabel, $typeCompany, 'Empresários');
    }

    public function index(Request $request)
    {
        $typeCompany = $request->get('type_company', '');
        $models = $this->process
            ->currentUser()
            ->where('type_company', $typeCompany)
            ->with('user', 'statusLatest')
            ->paginate(10);

        $title = $this->getTitleByTypeCompany($typeCompany);
        $gridData = [
            'models' => $models,
            'fields' => [
                'protocol',
                'user' => ['name'],
                'editing_human',
                'statusLatestFirst' => ['label'],
            ]
        ];

        return view('dashboard.process.grid')->with(compact('title', 'gridData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $title = 'Novo Processo';
        return view('dashboard.process.create')->with(compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $updating = Process::OPERATION_UPDATING;
        $transformation = Process::OPERATION_TRANSFORMATION;
        $data = $request->validate([
            'operation' => 'required',
            'type_company' => 'required',
            'fields_editing' => "nullable|array|required_if:operation,$updating",
            'new_type_company' => "nullable|required_if:operation,$transformation",
            'description' => 'nullable',
        ], [
            'operation.required' => 'O campo "Operação" é obrigatório.',
            'type_company.required' => 'O campo "Tipo de empresa" é obrigatório.',
            'fields_editing.required_if' => 'O campo "Campos que serão alterados" é obrigatório quando "Operação" for "Alteração".',
            'new_type_company.required_if' => 'O campo "Novo tipo de empresa" é obrigatório quando "Operação" for "Transformação".',
        ]);
        $defaultData = [ 'editing' => true ];
        if (auth()->user() && auth()->user()->isAdmin()) {
            $defaultData['user_id'] = $request->get('user_id', false);
        }
        $newProcess = $this->process->newInstance()->fill(array_merge($defaultData, $data));
        $newProcess->save();
        $newProcess->statuses()->attach(Status::getStatusStarting());

        return redirect()->route('dashboard.process.edit', [$newProcess]);
    }

    /**
     * Display the specified resource.
     *
     * @param Process $process
     * @return Response
     * @throws AuthorizationException
     */
    public function show(Process $process)
    {
        $title = $process->protocol;

        $statuses = $process->statuses;
        return view('dashboard.process.show')->with(compact('title', 'statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Process $process
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(Process $process)
    {
        $this->authorize('edit-process', $process);

        $title = $this->getTitleByTypeCompany($process->new_type_company ?? $process->type_company) . ' - ' . $process->protocol;

        $steps = [
            'admin' => [ 'label' => 'Administração' ],
            'owners' => [ 'label' => $this->getOwnersLabelByTypeCompany($process->new_type_company ?? $process->type_company) ],
            'company' => [ 'label' => 'Empresa' ],
            'viabilities' => [ 'label' => 'Questionário de Viabilidade' ],
            'document' => [ 'label' => 'Documentos' ],
            'finish' => [ 'label' => 'Finalizar' ],
        ];
        return view('dashboard.process.edit')->with(compact('title', 'steps', 'process'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Process $process
     * @return Response
     */
    public function update(Request $request, Process $process)
    {
        $owners = [];
        if ($ownersData = $this->getRequestOwners()) {
            $owners = $this->saveOwnersData($ownersData, $process);
        }

        $company = false;
        if ($companyData = $this->getRequestCompany(false, $process)) {
            $company = $this->saveCompanyData($companyData, $process);
        }

        $viability = false;
        if ($viabilityData = $this->getRequestViability()) {
            $viability = $this->saveViabilityData($viabilityData, $process);
        }

        $documents = [];
        if ($documentsData = $this->getRequestDocuments()) {
            $documents = $this->saveDocumentsData($documentsData, $process);
        }

        $url = false;
        if ($processData = $this->getRequestProcess()) {
            $url = $this->saveProcessData($processData, $process);
        }

        $validationErrors = $this->validationErrors;

        return compact('owners', 'company', 'viability', 'documents', 'validationErrors', 'url');
    }

    /**
     * @param bool $finish
     * @return array
     */
    private function getRequestOwners($finish = false)
    {
        $required = $finish ? 'required' : 'nullable';
        return request()->validate([
            'owners' => "{$required}|array",
            'owners.*.id' => $required,
            'owners.*.job_roles' => 'nullable|array',
            'owners.*.job_roles_other' => 'nullable|array',
            'owners.*.name' => $required,
            'owners.*.marital_status' => $required,
            'owners.*.wedding_regime' => 'nullable',
            'owners.*.rg' => $required,
            'owners.*.rg_expedition' => $required,
            'owners.*.date_of_birth' => $required,
            'owners.*.cpf' => $required,
            'owners.*.address' => 'required|array',
        ], [
            'owners.required' => 'É obrigatório informar ao menos um Empresário|Sócio|Integrante.',
            'owners.*.id.required' => '',
            'owners.*.name.required' => 'O campo Nome de Empresário|Sócio|Integrante é obrigatório.',
            'owners.*.marital_status.required' => 'O campo Estado Civil de Empresário|Sócio|Integrante é obrigatório.',
            'owners.*.rg.required' => 'O campo RG de Empresário|Sócio|Integrante é obrigatório.',
            'owners.*.rg_expedition.required' => 'O campo Data de Expedição de Empresário|Sócio|Integrante é obrigatório.',
            'owners.*.date_of_birth.required' => 'O campo Data de Nacimento de Empresário|Sócio|Integrante é obrigatório.',
            'owners.*.cpf.required' => 'O campo CPF de Empresário|Sócio|Integrante é obrigatório.',
            'owners.*.address.required' => 'É obrigatório informar os dados de endereço do Empresário|Sócio|Integrante',
        ]);
    }

    /**
     * @param array $ownersData
     * @param Process $process
     * @return Owner[]
     */
    private function saveOwnersData($ownersData, Process $process)
    {
        $ownersData = array_get($ownersData, 'owners', []);

        $owners = [];
        foreach ($ownersData as $key => $ownerData) {
            $ownerId = array_get($ownerData, 'id');
            $ownerDataExceptAddress = array_except($ownerData, ['id', 'address']);

            $ownerAddressId = array_get($ownerData, 'address.id');
            $ownerAddressData = array_except(array_get($ownerData, 'address', []), 'id');

            /** @var Owner $owner */
            $owner = $process->owners()->updateOrCreate(
                [ 'id' =>  $ownerId ],
                $ownerDataExceptAddress
            );

            /** @var Address $address */
            $address = $owner->address()->updateOrCreate(
                [ 'id' => $ownerAddressId ],
                $ownerAddressData
            );

            $owner->address()->associate($address);
            $owner->save();

            $owners[$key] = $owner;
        }
        return $owners;
    }

    /**
     * @param bool $finish
     * @param Process $process
     * @return array
     */
    private function getRequestCompany($finish = false, Process $process)
    {
        $required = $finish ? 'required' : 'nullable';
        return request()->validate([
            'company' => 'nullable|array',
            'company.id' => $required,
            'company.name' => $required,
            'company.nire' => $finish ? Rule::requiredIf(!$process->isCreating()) : 'nullable',
            'company.cnpj' => $finish ? Rule::requiredIf(!$process->isCreating()) : 'nullable',
            'company.share_capital' => $required,
            'company.activity_description' => $required,
            'company.size' => $required,
            'company.signed' => $required,
            'company.address' => 'nullable|array',
            'company.cnaes' => 'nullable|array',
        ], [
            'company.id.required' => '',
            'company.name.required' => 'O campo Nome de Empresa é obrigatório',
            'company.nire.required' => 'O campo NIRE de Empresa é obrigatório',
            'company.cnpj.required' => 'O campo CNPJ de Empresa é obrigatório',
            'company.share_capital.required' => 'O campo Capital Social de Empresa é obrigatório',
            'company.activity_description.required' => 'O campo Descrição da Atividade de Empresa é obrigatório',
            'company.size.required' => 'O campo Porte da Empresa de Empresa é obrigatório',
            'company.signed.required' => 'O campo Data de Assinatura de Empresa é obrigatório',
        ]);
    }

    /**
     * @param array $companyData
     * @param Process $process
     * @return Company
     */
    private function saveCompanyData($companyData, Process $process)
    {
        $companyData = array_get($companyData, 'company');

        $companyId = array_get($companyData, 'id');
        $companyDataExceptAddress = array_except($companyData, ['id', 'address']);

        $companyAddressId = array_get($companyData, 'address.id');
        $companyAddressData = array_except(array_get($companyData, 'address', []), 'id');

        $companyCnaesData = array_get($companyData, 'cnaes', []);

        /** @var Company $company */
        $company = $process->company()->updateOrCreate(
            [ 'id' =>  $companyId ],
            $companyDataExceptAddress
        );

        /** @var Address $address */
        $address = $company->address()->updateOrCreate(
            [ 'id' => $companyAddressId ],
            $companyAddressData
        );

        $company->address()->associate($address);
        $company->save();

        foreach ($companyCnaesData as $key => $companyCnaeData) {
            $companyCnaesId = array_get($companyCnaeData, 'id');

            $company->cnaes()->updateOrCreate(
                [ 'id' => $companyCnaesId ],
                $companyCnaeData
            );
        }

        return $company->loadMissing('cnaes');
    }

    /**
     * @param bool $finish
     * @return array
     */
    private function getRequestViability($finish = false)
    {
        $required = $finish ? 'required' : 'nullable';
        return request()->validate([
            'viability' => 'nullable|array',
            'viability.id' => 'nullable',
            'viability.property_type' => $required,
            'viability.registration_number' => $required,
            'viability.property_area' => $required,
            'viability.establishment_area' => $required,
            'viability.same_as_business_address' => $required,
            'viability.thirst' => $required,
            'viability.administrative_office' => $required,
            'viability.closed_deposit' => $required,
            'viability.warehouse' => $required,
            'viability.repair_workshop' => $required,
            'viability.garage' => $required,
            'viability.fuel_supply_unit' => $required,
            'viability.exposure_point' => $required,
            'viability.training_center' => $required,
            'viability.data_processing_center' => $required,
        ], [
            'viability.property_type.required' => 'O campo Tipo do imóvel de Questionario de Viabilidade é obrigatório',
            'viability.registration_number.required' => 'O campo Numero de cadastro de Questionario de Viabilidade é obrigatório',
            'viability.property_area.required' => 'O campo Área do imóvel de Questionario de Viabilidade é obrigatório',
            'viability.establishment_area.required' => 'O campo Área do estabelecimento de Questionario de Viabilidade é obrigatório',
            'viability.same_as_business_address.required' => 'O campo A atividade é exercida no mesmo local do endereço da empresa de Questionario de Viabilidade é obrigatório',
            'viability.thirst.required' => 'O campo Administração central da empresa, presidencia, diretoria de Questionario de Viabilidade é obrigatório',
            'viability.administrative_office.required' => 'O campo Estabelecimento onde são exercidas atividades meramente administratives de Questionario de Viabilidade é obrigatório',
            'viability.closed_deposit.required' => 'O campo Estabelecimento onde a empresa armazena mercadorias próprias destinadas à industrialização e/ou comercialização, no qual não se realizam vendas de Questionario de Viabilidade é obrigatório',
            'viability.warehouse.required' => 'O campo Estabelecimento onde a empresa armazena artigos de consumo para uso próprio de Questionario de Viabilidade é obrigatório',
            'viability.repair_workshop.required' => 'O campo Estabelecimento onde se efetua manutenção e reparação exclusivamente de bens do ativo fixo da própria empresa de Questionario de Viabilidade é obrigatório',
            'viability.garage.required' => 'O campo Para estabelecimento de veiculos próprios, uso exclusivo da empresa de Questionario de Viabilidade é obrigatório',
            'viability.fuel_supply_unit.required' => 'O campo Estabelecimento de abastecimento de combustiveis para uso pela frota própria de Questionario de Viabilidade é obrigatório',
            'viability.exposure_point.required' => 'O campo Estabelecimento para exposição e demonstração de produtos próprios, sem realização de transações comerciais, tipo showroom de Questionario de Viabilidade é obrigatório',
            'viability.training_center.required' => 'O campo Estabelecimento destinado a treinamento, de uso exclusivo da empresa, para realização de atividades de capacitação e treinamentos de recursos humanos de Questionario de Viabilidade é obrigatório',
            'viability.data_processing_center.required' => 'O campo Estabelecimento de processo de dados, de uso exclusivo da empresa, para realização de atividades na área de informática em geral de Questionario de Viabilidade é obrigatório',
        ]);
    }

    /**
     * @param $viabilityData
     * @param Process $process
     * @return Viability
     */
    private function saveViabilityData($viabilityData, Process $process)
    {
        $viabilityData = array_get($viabilityData, 'viability');

        $viabilityId = array_get($viabilityData, 'id');
        $viabilityData = array_except($viabilityData, ['id']);

        /** @var Viability $viability */
        $viability = $process->viability()->updateOrCreate(
            [ 'id' =>  $viabilityId ],
            $viabilityData
        );

        $process->viability()->associate($viability);
        $process->save();

        return $viability;
    }

    /**
     * @param bool $finish
     * @return array
     */
    private function getRequestDocuments($finish = false)
    {
        $required = $finish ? 'required' : 'nullable';
        return request()->validate([
            'documents' => 'nullable|array',
            'documents.*.id' => 'nullable',
            'documents.*.file.*' => 'nullable|mimes:jpg,png',
        ]);
    }

    /**
     * @param $documentsData
     * @param Process $process
     * @return Document[]
     */
    private function saveDocumentsData($documentsData, Process $process)
    {
        $documentsData = array_get($documentsData, 'documents');

        $documents = [];
        foreach ($documentsData as $type => $documentData) {
            $documentId = array_get($documentData, 'id');
            $documentFile = array_get($documentData, 'file');

            $documentFileArr = is_array($documentFile) ? $documentFile : [ $documentFile ];

            foreach ($documentFileArr as $file) {
                if (!$file) {
                    continue;
                }

                $pathName = $file->store('documents');
                $filename = is_string($pathName) ? str_replace('documents/', '', $pathName) : $pathName;

                $documents[$type] = $process->documents()->updateOrCreate(
                    [ 'id' =>  $documentId ],
                    [
                        'type' => $type,
                        'file' => $filename
                    ]
                );
            }
        }

        return $documents;
    }

    /**
     * @return array
     */
    private function getRequestProcess()
    {
        return request()->validate([
            'process' => 'nullable|array',
            'process.status' => 'nullable',
            'process.finished' => 'nullable|boolean',
            'process.scanned' => 'nullable|boolean',
            'process.post_office' => 'nullable|boolean',
        ]);
    }

    /**
     * @param $processData
     * @param Process $process
     * @return bool|string
     */
    private function saveProcessData($processData, Process $process)
    {
        $url = false;
        $processData = array_get($processData, 'process');

        $process->fill([
            'scanned' => array_get($processData, 'scanned', false),
            'post_office' => array_get($processData, 'post_office', false),
        ]);

        if (array_get($processData, 'finished')) {
            if ($this->validFinishEditingProcess($process)) {
                $process->fill(['editing' => false]);
                $process->statuses()->attach(array_get($processData, 'status') ?: Status::getStatusCompleted());
                $url = route('dashboard.process.index', [ 'type_company' => $process->type_company ]);

                event(new FinishProcess($process));
            }
        }

        $process->save();
        return $url;
    }

    /**
     * @param Process $process
     * @return bool
     */
    private function validFinishEditingProcess(Process $process)
    {
        try {
            $this->getRequestOwners(true);
            $this->getRequestCompany(true, $process);
            $this->getRequestDocuments(true);
            $this->getRequestViability(true);

            return true;
        } catch (ValidationException $e) {
            $this->validationErrors = $e->errors();
            return false;
        }
    }
}
