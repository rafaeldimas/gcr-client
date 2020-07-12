<?php

namespace Gcr\Http\Controllers\Dashboard;

use Gcr\Address;
use Gcr\Company;
use Gcr\Document;
use Gcr\Events\FinishProcess;
use Gcr\Http\Controllers\Controller;
use Gcr\Http\Requests\ProcessUpdateStatusRequest;
use Gcr\Owner;
use Gcr\Process;
use Gcr\Status;
use Gcr\Subsidiary;
use Gcr\Viability;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProcessController extends Controller
{
    /**
     * @var Process|Builder
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
            Process::TYPE_COMPANY_BUSINESSMAN => 'Empresário',
            Process::TYPE_COMPANY_SOCIETY => 'Sócios',
            Process::TYPE_COMPANY_EIRELI => 'Integrantes',
            Process::TYPE_COMPANY_OTHER => 'Integrantes',
        ];

        return array_get($ownersLabel, $typeCompany, 'Empresários');
    }

    public function updateStatus(ProcessUpdateStatusRequest $processUpdateStatusRequest, Process $process)
    {
        $data = $processUpdateStatusRequest->validated();
        $process->statuses()->attach(
            array_get($data, 'status'),
            array_only($data, 'description')
        );

        return redirect()->back();
    }

    public function index(Request $request)
    {
        $typeCompany = $request->get('type_company', '');
        $models = $this->process
            ->currentUser()
            ->where('type_company', $typeCompany)
            ->when($request->input('search.user_id'), function (Builder $builder, $userId) {
                return $builder->where('user_id', $userId);
            })
            ->when($request->input('search.protocol'), function (Builder $builder, $protocol) {
                return $builder->where('protocol', 'like', "%{$protocol}%");
            })
            ->when($request->input('search.company_name'), function (Builder $builder, $companyName) {
                return $builder->whereHas('company', function (Builder $builder) use ($companyName) {
                    $builder->where('name', 'like', "%{$companyName}%");
                });
            })
            ->when($request->input('search.company_cnpj'), function (Builder $builder, $companyCnpj) {
                return $builder->whereHas('company', function (Builder $builder) use ($companyCnpj) {
                    $builder->where('cnpj', 'like', "%{$companyCnpj}%");
                });
            })
            ->with('user', 'statusLatest', 'company')
            ->paginate(10);

        $title = $this->getTitleByTypeCompany($typeCompany);
        $gridData = [
            'models' => $models,
            'fields' => [
                'protocol',
                'user' => ['name'],
                'company' => ['name', 'cnpj'],
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

        $process = \request('process');
        if ($process) {
            $process = $this->process->find($process);

            $title = 'Editando dados iniciais do processo ' . $process->protocol;
        }

        return view('dashboard.process.create')->with(compact('title', 'process'));
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
            'process_id' => 'nullable',
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

        $process = $this->process->newInstance();
        if ($processId = array_get($data, 'process_id')) {
            $process = $this->process->find($processId);
        }

        $process->fill(array_merge($defaultData, $data));
        $process->save();

        if (!$processId) {
            $process->statuses()->attach(Status::getStatusStarting());
        }

        return redirect()->route('dashboard.process.edit', [$process]);
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
        return view('dashboard.process.show')->with(compact('title', 'statuses', 'process'));
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

        $process->load([
            'user',
            'viability',
            'statuses',
            'statusLatest',
            'owners.address',
            'company.address',
            'company.subsidiaries',
            'documents',
        ]);

        $title = $this->getTitleByTypeCompany($process->new_type_company ?? $process->type_company) . ' - ' . $process->protocol;

        $steps = [
            'owners' => [ 'label' => $this->getOwnersLabelByTypeCompany($process->new_type_company ?? $process->type_company) ],
            'company' => [ 'label' => 'Empresa' ],
            'subsidiaries' => [ 'label' => 'Filiais' ],
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
        if ($ownersData = $this->getRequestOwners(false, $process)) {
            $owners = $this->saveOwnersData($ownersData, $process);
        }

        $company = false;
        if ($companyData = $this->getRequestCompany(false, $process)) {
            $company = $this->saveCompanyData($companyData, $process);
        }

        $subsidiaries = [];
        if ($subsidiariesData = $this->getRequestSubsidiaries(false, $process)) {
            $subsidiaries = $this->saveSubsidiariesData($subsidiariesData, $process);
        }

        $viability = false;
        if ($viabilityData = $this->getRequestViability(false, $process)) {
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

        return compact('owners', 'company', 'subsidiaries', 'viability', 'documents', 'validationErrors', 'url');
    }

    /**
     * @param bool $finish
     * @param Process $process
     * @return array
     */
    private function getRequestOwners($finish, Process $process)
    {
        $required = $finish ? 'required' : 'nullable';
        return request()->validate([
            'owners' => [
                Rule::requiredIf(function () use ($finish, $process) {
                    if (!$finish) {
                        return false;
                    }

                    return !$process->isDeleting() && (!$process->isUpdating() || $process->isEditingOwners());
                }),
                'array',
            ],
            'owners.*.id' => $required,
            'owners.*.job_roles' => 'nullable|array',
            'owners.*.change_type' => 'nullable|string',
            'owners.*.job_roles_other' => 'nullable|string',
            'owners.*.name' => $required,
            'owners.*.share_capital' => 'nullable',
            'owners.*.marital_status' => $required,
            'owners.*.wedding_regime' => 'nullable',
            'owners.*.rg' => $required,
            'owners.*.rg_expedition' => $required,
            'owners.*.date_of_birth' => $required,
            'owners.*.cpf' => $required,
            'owners.*.address' => 'required|array',
        ], [
            'owners.required_if' => 'É obrigatório informar ao menos um Empresário|Sócio|Integrante.',
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
    private function getRequestCompany($finish, Process $process)
    {
        $required = $finish ? 'required' : 'nullable';
        return request()->validate([
            'company' => 'nullable|array',
            'company.id' => $required,
            'company.transformation_with_change' => 'nullable|array',
            'company.name' => $required,
            'company.nire' => Rule::requiredIf(function () use($finish, $process) {
                return $finish && !$process->isCreating();
            }),
            'company.cnpj' => Rule::requiredIf(function () use($finish, $process) {
                return $finish && !$process->isCreating();
            }),
            'company.activity_start' => Rule::requiredIf(function () use($finish, $process) {
                return $finish && $process->isCreating();
            }),
            'company.share_capital' => Rule::requiredIf(function () use($finish, $process) {
                return $finish && (!$process->isDeleting() && (!$process->isUpdating() || $process->isEditingCompany() || $process->isEditingCapital()));
            }),
            'company.activity_description' => Rule::requiredIf(function () use($finish, $process) {
                return $finish && (!$process->isDeleting() && (!$process->isUpdating() || $process->isEditingCompanyCnaes()));
            }),
            'company.size' => Rule::requiredIf(function () use($finish, $process) {
                return $finish && (!$process->isDeleting() && (!$process->isUpdating() || $process->isEditingCompany() || $process->isEditingCompanySize()));
            }),
            'company.signed' => $required,
            'company.address' => [
                Rule::requiredIf(function () use($finish, $process) {
                    return $finish && (!$process->isDeleting() && (!$process->isUpdating() || $process->isEditingCompanyAddress() || $process->isEditingTransferToAnotherUf() || $process->isEditingTransferFromAnotherUfToSp()));
                }),
                'array'
            ],
            'company.cnaes' => [
                Rule::requiredIf(function () use($finish, $process) {
                    return $finish && (!$process->isDeleting() && (!$process->isUpdating() || $process->isEditingCompanyCnaes()));
                }),
                'array'
            ],
        ], [
            'company.id.required' => '',
            'company.name.required' => 'O campo Nome Empresarial de Empresa é obrigatório',
            'company.nire.required_if' => 'O campo NIRE de Empresa é obrigatório',
            'company.cnpj.required_if' => 'O campo CNPJ de Empresa é obrigatório',
            'company.activity_start.required_if' => 'O campo Data de início da atividade de Empresa é obrigatório',
            'company.share_capital.required_if' => 'O campo Capital Social de Empresa é obrigatório',
            'company.activity_description.required_if' => 'O campo Descrição da Atividade de Empresa é obrigatório',
            'company.size.required_if' => 'O campo Porte da Empresa de Empresa é obrigatório',
            'company.signed.required' => 'O campo Data de Assinatura de Empresa é obrigatório',
            'company.address.required_if' => 'Os campos de Endereço de Empresa é obrigatório',
            'company.cnaes.required_if' => 'É obrigatório informar ao menos um cnae da Empresa.',
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

        if ($companyAddressData) {
            /** @var Address $address */
            $address = $company->address()->updateOrCreate(
                [ 'id' => $companyAddressId ],
                $companyAddressData
            );

            $company->address()->associate($address);
            $company->save();
        }

        foreach ($companyCnaesData as $key => $companyCnaeData) {
            $companyCnaeId = array_get($companyCnaeData, 'id');
            $companyCnaeNumber = array_get($companyCnaeData, 'number');

            if ($companyCnaeId && empty($companyCnaeNumber)) {
                $company->cnaes()->find($companyCnaeId)->delete();
            }

            if (empty($companyCnaeNumber)) {
                continue;
            }

            $company->cnaes()->updateOrCreate(
                [ 'id' => $companyCnaeId ],
                $companyCnaeData
            );
        }

        return $company->loadMissing('cnaes');
    }


    /**
     * @param bool $finish
     * @param Process $process
     * @return array
     */
    private function getRequestSubsidiaries($finish, Process $process)
    {
        $required = $finish && $process->isEditingSubsidiary() ? 'required' : 'nullable';
        return request()->validate([
            'subsidiaries' => "{$required}|array",
            'subsidiaries.*.id' => $required,
            'subsidiaries.*.request' => "{$required}|integer|in:".implode(',', Subsidiary::attributeCodes('request')),
            'subsidiaries.*.fields_changed' => !$finish ? 'nullable' : "required_if:subsidiaries.*.request,3|array",
            'subsidiaries.*.nire' => !$finish ? 'nullable' : 'required_if:subsidiaries.*.request,2|required_if:subsidiaries.*.fields_changed,1,2,3|string',
            'subsidiaries.*.cnpj' => !$finish ? 'nullable' : 'required_if:subsidiaries.*.request,2|required_if:subsidiaries.*.fields_changed,1,2,3|string',
            'subsidiaries.*.share_capital' => !$finish ? 'nullable' : 'required_if:subsidiaries.*.request,1|required_if:subsidiaries.*.fields_changed,3|string',
            'subsidiaries.*.activity_description' => !$finish ? 'nullable' : 'required_if:subsidiaries.*.request,1|required_if:subsidiaries.*.fields_changed,1|string',
            'subsidiaries.*.address' => !$finish ? 'nullable' : 'required_if:subsidiaries.*.request,1|required_if:subsidiaries.*.fields_changed,2|array',
            'subsidiaries.*.cnaes' => !$finish ? 'nullable' : 'required_if:subsidiaries.*.request,1|required_if:subsidiaries.*.fields_changed,1|array',
        ], [
            'subsidiaries.required' => 'É obrigatório informar ao menos uma Filial.',
            'subsidiaries.*.id.required' => '',
            'subsidiaries.*.requests.required' => 'O campo Tipo de Solicitação das Filiais é obrigatório.',
            'subsidiaries.*.nire.required_if' => 'O campo NIRE das Filiais é obrigatório quando o campo Tipo de Solicitação for Cancelamento, Alteração Atividade, Alteração Endereço, Alteração Capital.',
            'subsidiaries.*.cnpj.required_if' => 'O campo CNPJ das Filiais é obrigatório quando o campo Tipo de Solicitação for Cancelamento, Alteração Atividade, Alteração Endereço, Alteração Capital.',
            'subsidiaries.*.share_capital.required_if' => 'O campo Capital Social das Filiais é obrigatório quando o campo Tipo de Solicitação for Abertura ou  Alteração Capital.',
            'subsidiaries.*.activity_description.required_if' => 'O campo Descrição da Atividade das Filiais é obrigatório quando o campo Tipo de Solicitação for Abertura ou Alteração Atividade.',
            'subsidiaries.*.address.required_if' => 'É obrigatório informar os dados de endereço das Filiais quando o campo Tipo de Solicitação for Abertura ou Alteração Endereço',
            'subsidiaries.*.cnaes.required_if' => 'É obrigatório informar os dados de cnaes das Filiais quando o campo Tipo de Solicitação for Abertura ou Alteração Atividade',
        ]);
    }

    /**
     * @param array $subsidiariesData
     * @param Process $process
     * @return Subsidiary[]
     */
    private function saveSubsidiariesData($subsidiariesData, Process $process)
    {
        $subsidiariesData = array_get($subsidiariesData, 'subsidiaries', []);

        $subsidiaries = [];
        foreach ($subsidiariesData as $key => $subsidiaryData) {
            $subsidiaryId = array_get($subsidiaryData, 'id');
            $subsidiaryDataExceptAddress = array_except($subsidiaryData, ['id', 'address']);

            $subsidiaryAddressId = array_get($subsidiaryData, 'address.id');
            $subsidiaryAddressData = array_except(array_get($subsidiaryData, 'address', []), 'id');

            /** @var Subsidiary $subsidiary */
            $subsidiary = $process->company->subsidiaries()->updateOrCreate(
                [ 'id' =>  $subsidiaryId ],
                $subsidiaryDataExceptAddress
            );

            if ($subsidiaryAddressData) {
                /** @var Address $address */
                $address = $subsidiary->address()->updateOrCreate(
                    [ 'id' => $subsidiaryAddressId ],
                    $subsidiaryAddressData
                );

                $subsidiary->address()->associate($address);
                $subsidiary->save();
            }

            $subsidiaryCnaesData = array_get($subsidiaryData, 'cnaes', []);

            foreach ($subsidiaryCnaesData as $key => $subsidiaryCnaeData) {
                $subsidiaryCnaeId = array_get($subsidiaryCnaeData, 'id');
                $subsidiaryCnaeNumber = array_get($subsidiaryCnaeData, 'number');

                if ($subsidiaryCnaeId && empty($subsidiaryCnaeNumber)) {
                    $subsidiary->cnaes()->find($subsidiaryCnaeId)->delete();
                }

                if (empty($subsidiaryCnaeNumber)) {
                    continue;
                }

                $subsidiary->cnaes()->updateOrCreate(
                    [ 'id' => $subsidiaryCnaeId ],
                    $subsidiaryCnaeData
                );
            }

            $subsidiaries[$key] = $subsidiary->loadMissing('cnaes');
        }
        return $subsidiaries;
    }

    /**
     * @param bool $finish
     * @param Process $process
     * @return array
     */
    private function getRequestViability($finish, Process $process)
    {
        $required = $finish && $process->showViability() ? 'required' : 'nullable';
        return request()->validate([
            'viability' => 'nullable|array',
            'viability.id' => 'nullable',
            'viability.property_type' => $required,
            'viability.registration_number' => $required,
            'viability.property_area' => $required,
            'viability.establishment_area' => $required,
            'viability.establishment_has_avcb_clcb' => $required,
            'viability.avcb_clcb_number_type' => $finish ? 'required_if:establishment_has_avcb_clcb,1' : 'nullable',
            'viability.avcb_clcb_number' => $finish ? 'required_if:establishment_has_avcb_clcb,1' : 'nullable',
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
            'viability.establishment_has_avcb_clcb.required' => 'O campo Local do estabelecimento possui AVCB/CLCB de Questionario de Viabilidade é obrigatório',
            'viability.avcb_clcb_number.required_if' => 'O campo Número AVCB/CLCB de Questionario de Viabilidade é obrigatório quando o campo Local do estabelecimento possui AVCB/CLCB é sim',
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
            'process.finished' => 'nullable|boolean',
            'process.scanned' => 'nullable|boolean',
            'process.post_office' => 'nullable|boolean',
            'process.sign_digital_certificate' => 'nullable|boolean',
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
            'sign_digital_certificate' => array_get($processData, 'sign_digital_certificate', false),
        ]);

        if (array_get($processData, 'finished')) {
            if ($this->validFinishEditingProcess($process)) {
                $process->fill(['editing' => false]);
                $process->statuses()->attach(
                    Status::getStatusCompleted(),
                    [ 'description' => 'Processo finalizado edição.' ]
                );
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
            $this->getRequestOwners(true, $process);
            $this->getRequestCompany(true, $process);
            $this->getRequestSubsidiaries(true, $process);
            $this->getRequestDocuments(true);
            $this->getRequestViability(true, $process);

            return true;
        } catch (ValidationException $e) {
            $this->validationErrors = $e->errors();
            return false;
        }
    }
}
