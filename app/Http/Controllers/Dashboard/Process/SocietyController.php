<?php

namespace Gcr\Http\Controllers\Dashboard\Process;

use Gcr\Process;
use Illuminate\Http\Request;
use Gcr\Http\Controllers\Controller;

class SocietyController extends Controller
{

    /**
     * @var Process
     */
    private $process;

    public function __construct(Process $process)
    {
        $this->process = $process->where('type', 'society');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Sociedade Limitada';
        $gridData = [
            'models' => $this->process->currentUser()->with('user')->paginate(10),
            'linkEdit' => 'dashboard.process.society.edit',
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
