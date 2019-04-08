<?php

namespace Gcr\Http\Controllers\Dashboard;

use Gcr\Address;
use Gcr\Company;
use Gcr\Http\Controllers\Controller;
use Gcr\Owner;
use Gcr\Process;
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

    private function getTitleByType($type)
    {
        $types = [
            'businessman' => 'Empresário individual',
            'society' => 'Sociedade Limitada',
            'eireli' => 'Eireli',
            'other' => 'Outros',
        ];
        return array_get($types, $type, 'Processo');
    }

    public function index(Request $request)
    {
        $typeCompany = $request->get('type_company', '');
        $models = $this->process
            ->currentUser()
            ->where('type_company', $typeCompany)
            ->with('user')
            ->paginate(10);

        $title = $this->getTitleByType($typeCompany);
        $gridData = [
            'models' => $models,
            'linkEdit' => 'dashboard.process.edit',
            'fields' => [
                'protocol',
                'user' => ['name'],
                'status',
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
        $data = $request->validate([
            'type' => 'required',
            'type_company' => 'required',
        ]);
        $newProcess = $this->process->newInstance();
        $newProcess->fill(array_merge([
            'status' => false,
            'description' => '',
        ], $data))->save();

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
        $title = $this->getTitleByType($process->type_company) . ' - ' . $process->protocol;

        $steps = [
            'owner' => [ 'label' => 'Empresário' ],
            'company' => [ 'label' => 'Empresa' ],
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
        if ($ownerData = $this->getRequestOwner()) {
            $this->saveOwnerData($ownerData, $process);
        }

        if ($companyData = $this->getRequestCompany()) {
            $this->saveCompanyData($companyData, $process);
        }
    }

    private function getRequestOwner()
    {
        return request()->validate([
            'owner.id' => 'nullable',
            'owner.name' => 'nullable',
            'owner.marital_status' => 'nullable',
            'owner.rg' => 'nullable',
            'owner.rg_expedition' => 'nullable',
            'owner.cpf' => 'nullable',
            'owner.address' => 'nullable|array',
        ]);
    }

    private function saveOwnerData($ownerData, Process $process)
    {
        $ownerData = array_get($ownerData, 'owner');

        $ownerId = array_get($ownerData, 'id');
        $ownerDataExceptAddress = array_except($ownerData, ['id', 'address']);

        $ownerAddressId = array_get($ownerData, 'address.id');
        $ownerAddressData = array_except(array_get($ownerData, 'address'), 'id');

        /** @var Owner $owner */
        $owner = $process->owner()->updateOrCreate(
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
    }

    private function getRequestCompany()
    {
        return request()->validate([
            'company.id' => 'nullable',
            'company.name' => 'nullable',
            'company.share_capital' => 'nullable',
            'company.activity_description' => 'nullable',
            'company.size' => 'nullable',
            'company.signed' => 'nullable',
            'company.address' => 'nullable|array',
        ]);
    }

    private function saveCompanyData($companyData, Process $process)
    {
        $companyData = array_get($companyData, 'company');

        $companyId = array_get($companyData, 'id');
        $companyDataExceptAddress = array_except($companyData, ['id', 'address']);

        $companyAddressId = array_get($companyData, 'address.id');
        $companyAddressData = array_except(array_get($companyData, 'address'), 'id');

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
