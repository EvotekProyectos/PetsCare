<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permisos = Permission::all();
        $this->authorize("viewAny", User::class);
        return view('user.index', compact('permisos'));
    }

    public function list(){
        $users = User::with('roles')->get();
        return DataTables::of($users) ->make(true);
    }

    public function getPermissionsForUsers($id)
    {
        $user = User::find($id);
        $this->authorize("viewPermissions", $user);
        $permissions = $user->getAllPermissions();

        return [$permissions, $user];
    }

    public function changePermissions(Request $request, int $id)
    {
        // dd($request->all());
        // // die;
        $user = User::find($id);
        $this->authorize("updatePermissions", $user);

        $user->syncPermissions($request->permissions);

        // Session::flash('success', 'Permisos actualizados correctamente.');
        return response()->json();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = new User();
        return view('user.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        User::create($request->validated());

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user =  User::find($id);
        $user->delete();

        return response()->json($user);
        // return redirect()->route('users.index')
        //     ->with('success', 'User deleted successfully');
    }
}
