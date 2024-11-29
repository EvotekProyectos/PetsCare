<?php

namespace App\Http\Controllers;

use App\Models\RoleHasPermission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\RoleHasPermissionRequest;
use Illuminate\Http\Request;

/**
 * Class RoleHasPermissionController
 * @package App\Http\Controllers
 */
class RoleHasPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', RoleHasPermission::class);
        $roles = Role::with('permissions')->where('name', '!=', 'administrador')->get();
        $permisos = Permission::all();

        return view('role-has-permission.index', compact('permisos', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
        $roleHasPermission = new RoleHasPermission();
        return view('role-has-permission.create', compact('roleHasPermission'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('update', RoleHasPermission::class);
        // Obtén todos los roles del request
        $rolesRequest = $request->all();
        // Recorre cada rol en el request
        foreach ($rolesRequest as $roleName => $permissions) {
            // Encuentra el rol por su nombre
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                // Extrae solo los nombres de los permisos
                $permissionNames = array_column($permissions, 'name');

                // Sincroniza los permisos del rol
                $role->syncPermissions($permissionNames);
            }
        }

        return redirect()->route('role-has-permissions.index')
            ->with('success', 'Permisos actualizados correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        abort(404);
        $roleHasPermission = RoleHasPermission::find($id);

        return view('role-has-permission.show', compact('roleHasPermission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        abort(404);
        $roleHasPermission = RoleHasPermission::find($id);

        return view('role-has-permission.edit', compact('roleHasPermission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleHasPermissionRequest $request, RoleHasPermission $roleHasPermission)
    {
        abort(404);

        return redirect()->route('role-has-permissions.index')
            ->with('success', 'Permisos actualizados correctamente');
    }

    public function destroy($id)
    {
        abort(404);
        RoleHasPermission::find($id)->delete();

        return redirect()->route('role-has-permissions.index')
            ->with('success', 'RoleHasPermission deleted successfully');
    }
}
