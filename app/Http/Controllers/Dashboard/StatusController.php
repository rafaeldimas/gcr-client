<?php

namespace Gcr\Http\Controllers\Dashboard;

use Exception;
use Gcr\Status;
use Illuminate\Http\Request;
use Gcr\Http\Controllers\Controller;
use Illuminate\Http\Response;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Status $status
     * @return Response
     */
    public function index(Status $status)
    {
        $title = 'Status';
        $gridData = [
            'models' => $status->paginate(10),
            'fields' => [ 'label', 'color', 'text_white_human' ]
        ];
        return view('dashboard.status.grid')->with(compact('title', 'gridData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $title = 'Novo Status';
        return view('dashboard.status.create')->with(compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => 'required',
            'color' => 'required',
            'text_white' => 'nullable|boolean',
        ], [
            'label.required' => 'O campo "Nome" é obrigatório.',
            'color.required' => 'O campo "Cor" é obrigatório.',
        ]);

        $defaultData = [ 'text_white' => false ];

        $status = new Status;
        $status->fill(array_merge($defaultData, $data))->save();

        return redirect()->route('dashboard.status.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Status $status
     * @return Response
     */
    public function edit(Status $status)
    {
        $title = "Editando status: {$status->label}";
        return view('dashboard.status.edit')->with(compact('title', 'status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Status $status
     * @return Response
     */
    public function update(Request $request, Status $status)
    {
        $data = $request->validate([
            'label' => 'required',
            'color' => 'required',
            'text_white' => 'nullable|boolean',
        ], [
            'label.required' => 'O campo "Nome" é obrigatório.',
            'color.required' => 'O campo "Cor" é obrigatório.',
        ]);

        $defaultData = [ 'text_white' => false ];

        $status->fill(array_merge($defaultData, $data))->save();

        return redirect()->route('dashboard.status.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Status $status
     * @return Response
     * @throws Exception
     */
    public function destroy(Status $status)
    {
        $errors = [];
        if (in_array($status->id, [1, 2])) {
            $errors['invalid_id'] = 'Não é possivel excluir os Status iniciais.';
        }

        if (!$errors) {
            $status->delete();
        }

        return redirect()->route('dashboard.status.index')->withErrors($errors);
    }
}
