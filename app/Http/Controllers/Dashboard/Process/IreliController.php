<?php

namespace Gcr\Http\Controllers\Dashboard\Process;

use Gcr\Process;
use Illuminate\Http\Request;
use Gcr\Http\Controllers\Controller;
use Illuminate\Http\Response;

class IreliController extends Controller
{
    /**
     * @var Process
     */
    private $process;

    public function __construct(Process $process)
    {
        $this->process = $process->where('type', 'ireli');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = 'Ireli';
        $gridData = [
            'models' => $this->process->with('user')->paginate(10),
            'linkEdit' => 'dashboard.process.ireli.edit',
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
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
