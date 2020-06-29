<?php

namespace Gcr\Http\Controllers\Dashboard;

use Carbon\Carbon;
use Exception;
use Gcr\Accounting;
use Gcr\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Gcr\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

class UserController extends Controller
{
    /**
     * @var User|Builder
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function current()
    {
        $user = auth()->user();
        return redirect()->route('dashboard.user.edit', compact('user'));
    }

    /**
     * @return Response
     */
    public function firstTimeLoginShow()
    {
        $user = auth()->user();
        return view('dashboard.user.first_time_login_show')->with(compact('user'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function firstTimeLogin(Request $request, User $user)
    {
        $data = $request->validate([
            'logo' => 'nullable|mimes:jpeg,jpg,png',
            'phone' => 'nullable',
            'mobile_phone' => 'nullable',
            'password' => 'required|string|min:8|confirmed',
            'accounting.name' => 'nullable',
            'accounting.email_1' => 'required|email',
            'accounting.email_2' => 'nullable|email',
            'accounting.email_3' => 'nullable|email',
            'accounting.email_4' => 'nullable|email',
            'accounting.email_5' => 'nullable|email',
            'accounting.address' => 'required|array',
        ]);

        $data = $this->storeLogo($data);

        $data['password'] = $data['password'] ? bcrypt($data['password']) : false;
        $data['password_change_at'] = now();

        $data = array_filter($data);

        $user->fill(array_except($data, 'accounting'));

        /** @var Accounting $accounting */
        $accounting = $user->accounting()->updateOrCreate(
            [ 'email_1' => array_get($data, 'accounting.email_1') ],
            array_except(array_get($data, 'accounting'), 'address')
        );

        $address = $accounting->address()->updateOrCreate(
            [ 'id' => array_get($data, 'accounting.address.id') ],
            array_except(array_get($data, 'accounting.address'), 'id')
        );

        $accounting->address()->associate($address);
        $accounting->save();

        $user->accounting()->associate($accounting);
        $user->save();

        return redirect()->route('dashboard.process.create');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = 'Usúarios';
        $gridData = [
            'models' => $this->user->with('accounting')->withCount('processes')->paginate(10),
            'linkEdit' => 'dashboard.user.edit',
            'fields' => [
                'name',
                'email',
                'accounting' => [ 'name' ],
                'type_label',
                'processes_count',
            ]
        ];
        return view('dashboard.user.grid')->with(compact('title', 'gridData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $title = 'Novo Usúario';
        return view('dashboard.user.create')->with(compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'type' => 'nullable',
            'logo' => 'nullable|mimes:jpeg,jpg,png',
            'phone' => 'nullable',
            'mobile_phone' => 'nullable',
            'accounting.name' => 'nullable',
            'accounting.email_1' => 'nullable|email',
            'accounting.email_2' => 'nullable|email',
            'accounting.email_3' => 'nullable|email',
            'accounting.email_4' => 'nullable|email',
            'accounting.email_5' => 'nullable|email',
            'accounting.address' => 'nullable|array',
        ]);

        if (!array_get($data, 'type', false)) {
            $data['type'] = User::TYPE_CUSTOMER;
        }

        $data = $this->storeLogo($data);

        $data['password'] = bcrypt($data['password']);

        $user = $this->user->newInstance()->fill(array_except($data, 'accounting'));

        /** @var Accounting $accounting */
        $accounting = $user->accounting()->updateOrCreate(
            [ 'email_1' => array_get($data, 'accounting.email_1') ],
            array_except(array_get($data, 'accounting'), 'address')
        );

        $address = $accounting->address()->updateOrCreate(
            [ 'id' => array_get($data, 'accounting.address.id') ],
            array_except(array_get($data, 'accounting.address'), 'id')
        );

        $accounting->address()->associate($address);
        $accounting->save();

        $user->accounting()->associate($accounting);
        $user->save();

        event(new Registered($user));

        return redirect()->route('dashboard.user.index');
    }

    private function storeLogo(array $data)
    {
        /** @var UploadedFile $file */
        if ($file = array_get($data, 'logo', false)) {
            $data['logo'] = $file->store('public/client/logo');
        }
        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user)
    {
        $user->load('accounting.address');
        $title = "Editar Usúario: {$user->name}";
        return view('dashboard.user.edit')->with(compact('title', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'nullable',
            'password' => 'nullable|string|min:8|confirmed',
            'logo' => 'nullable|mimes:jpeg,jpg,png',
            'phone' => 'nullable',
            'mobile_phone' => 'nullable',
            'accounting.name' => 'nullable',
            'accounting.email_1' => 'required|email',
            'accounting.email_2' => 'nullable|email',
            'accounting.email_3' => 'nullable|email',
            'accounting.email_4' => 'nullable|email',
            'accounting.email_5' => 'nullable|email',
            'accounting.address' => 'required|array',
        ]);

        $data = $this->storeLogo($data);

        if ($data['password']) {
            $data['password'] = bcrypt($data['password']);
        }

        $data = array_filter($data);

        $user->fill(array_except($data, 'accounting'));

        /** @var Accounting $accounting */
        $accounting = $user->accounting()->updateOrCreate(
            [ 'email_1' => array_get($data, 'accounting.email_1') ],
            array_except(array_get($data, 'accounting'), 'address')
        );

        $address = $accounting->address()->updateOrCreate(
            [ 'id' => array_get($data, 'accounting.address.id') ],
            array_except(array_get($data, 'accounting.address'), 'id')
        );

        $accounting->address()->associate($address);
        $accounting->save();

        $user->accounting()->associate($accounting);
        $user->save();

        return redirect()->route('dashboard.user.edit', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return Response
     * @throws Exception
     */
    public function destroy(User $user)
    {
        if (!optional(auth()->user())->isAdmin()) {
            return redirect()->back();
        }

        $user->delete();
        return redirect()->back();
    }
}
