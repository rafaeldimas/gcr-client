<?php

namespace Gcr\Http\Controllers\Dashboard;

use Gcr\Address;
use Gcr\Company;
use Gcr\Cnae;
use Gcr\Http\Controllers\Controller;
use Gcr\Owner;
use Gcr\Process;
use Gcr\Status;
use Gcr\Viability;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    /**
     * @var Process
     */
    private $process;

    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    private function getTitleByTypeCompany($typeCompany)
    {
        $typesCompany = Process::attributeOptions('type_company');
        return array_get($typesCompany, $typeCompany, 'Processo');
    }

    public function index(Request $request)
    {
        $typeCompany = $request->get('type_company', '');
        $models = $this->process
            ->currentUser()
            ->where('type_company', $typeCompany)
            ->with('user')
            ->paginate(10);

        $title = $this->getTitleByTypeCompany($typeCompany);
        $gridData = [
            'models' => $models,
            'linkEdit' => 'dashboard.process.edit',
            'fields' => [
                'protocol',
                'user' => ['name'],
                'editing',
                'status' => ['label'],
            ]
        ];
        return view('dashboard.process.grid')->with(compact('title', 'gridData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Novo Processo';
        return view('dashboard.process.create')->with(compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $updating = Process::OPERATION_UPDATING;
        $data = $request->validate([
            'operation' => 'required',
            'type_company' => 'required',
            'fields_editing' => "nullable|array|required_if:operation,$updating",
            'description' => 'nullable',
        ], [
            'operation.required' => 'O campo "Operação" é obrigatório.',
            'type_company.required' => 'O campo "Tipo de empresa" é obrigatório.',
            'fields_editing.required_if' => 'O campo "Campos que serão alterados" é obrigatório quando "Operação" for "Alteração".',
            'description' => 'nullable',
        ]);

        $newProcess = $this->process->newInstance()->fill(array_merge([
            'editing' => true,
        ], $data))->status()->associate(Status::getStatusStarting());
        $newProcess->save();

        return redirect()->route('dashboard.process.edit', [$newProcess]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Gcr\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function show(Process $process)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Gcr\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function edit(Process $process)
    {
        $title = $this->getTitleByTypeCompany($process->type_company) . ' - ' . $process->protocol;

        $steps = [
            'owners' => [ 'label' => 'Empresário' ],
            'company' => [ 'label' => 'Empresa' ],
            'viabilities' => [ 'label' => 'Questionário de Viabilidade' ],
            'document' => [ 'label' => 'Documentos' ]
        ];
        return view('dashboard.process.edit')->with(compact('title', 'steps', 'process'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Gcr\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Process $process)
    {
        $owners = [];
        if ($ownersData = $this->getRequestOwners()) {
            $owners = $this->saveOwnersData($ownersData, $process);
        }

        $company = false;
        if ($companyData = $this->getRequestCompany()) {
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
        if ($request->has('finished')) {
            $process->fill([ 'editing' => false])->status()->associate(Status::getStatusCompleted());
            $process->save();
            $url = route('dashboard.process.index', [ 'type_company' => $process->type_company ]);
        }

        return compact('owners', 'company', 'viability', 'documents', 'url');
    }

    private function getRequestOwners()
    {
        return request()->validate([
            'owners' => 'nullable|array',
            'owners.*.id' => 'nullable',
            'owners.*.name' => 'nullable',
            'owners.*.marital_status' => 'nullable',
            'owners.*.wedding_regime' => 'nullable',
            'owners.*.rg' => 'nullable',
            'owners.*.rg_expedition' => 'nullable',
            'owners.*.date_of_birth' => 'nullable',
            'owners.*.cpf' => 'nullable',
            'owners.*.address' => 'nullable|array',
        ]);
    }

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

    private function getRequestCompany()
    {
        return request()->validate([
            'company' => 'nullable|array',
            'company.id' => 'nullable',
            'company.name' => 'nullable',
            'company.share_capital' => 'nullable',
            'company.activity_description' => 'nullable',
            'company.size' => 'nullable',
            'company.signed' => 'nullable',
            'company.address' => 'nullable|array',
            'company.cnaes' => 'nullable|array',
        ]);
    }

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

    private function getRequestViability()
    {
        return request()->validate([
            'viability' => 'nullable|array',
            'viability.id' => 'nullable',
            'viability.property_type' => 'nullable',
            'viability.registration_number' => 'nullable',
            'viability.property_area' => 'nullable',
            'viability.establishment_area' => 'nullable',
            'viability.same_as_business_address' => 'nullable',
            'viability.thirst' => 'nullable',
            'viability.administrative_office' => 'nullable',
            'viability.closed_deposit' => 'nullable',
            'viability.warehouse' => 'nullable',
            'viability.repair_workshop' => 'nullable',
            'viability.garage' => 'nullable',
            'viability.fuel_supply_unit' => 'nullable',
            'viability.exposure_point' => 'nullable',
            'viability.training_center' => 'nullable',
            'viability.data_processing_center' => 'nullable',
        ]);
    }

    private function saveViabilityData($viabilityData, Process $process)
    {
        $viabilityData = array_get($viabilityData, 'viability');

        $viabilityId = array_get($viabilityData, 'id');
        $viabilityData = array_except($viabilityData, ['id']);

        $viability = $process->viability()->updateOrCreate(
            [ 'id' =>  $viabilityId ],
            $viabilityData
        );

        $process->viability()->associate($viability);
        $process->save();

        return $viability;
    }

    private function getRequestDocuments()
    {
        return request()->validate([
            'documents' => 'nullable|array',
            'documents.*.id' => 'nullable',
            'documents.*.file.*' => 'nullable|mimes:jpg,png',
        ]);
    }

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
     * Remove the specified resource from storage.
     *
     * @param  \Gcr\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function destroy(Process $process)
    {
        //
    }
}
