<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create users permissions
        Permission::create(['name'=> 'ver panel usuarios']);
        Permission::create(['name' => 'crear usuarios']);
        Permission::create(['name' =>'editar usuarios']);
        Permission::create(['name' => 'eliminar usuarios']);

        // create rooms permissions
        Permission::create(['name'=> 'ver panel consultorios']);
        Permission::create(['name'=> 'crear consultorios']);
        Permission::create(['name'=> 'editar consultorios']);
        Permission::create(['name'=> 'eliminar consultorios']);

        // create permissions 
        Permission::create(['name' => 'ver permisos usuarios']);
        Permission::create(['name' => 'editar permisos usuarios']);

        // create logs permissions
        Permission::create(['name' => 'ver panel bitácora']);

        // create reasons permissions
        Permission::create(['name'=> 'ver panel motivos']);
        Permission::create(['name' => 'crear motivos']);
        Permission::create(['name' =>'editar motivos']);
        Permission::create(['name' => 'eliminar motivos']);

        // create areas permissions
        Permission::create(['name'=> 'ver panel areas']);
        Permission::create(['name' => 'crear areas']);
        Permission::create(['name' =>'editar areas']);
        Permission::create(['name' => 'eliminar areas']);

        // create genres permissions
        Permission::create(['name'=> 'ver panel generos']);
        Permission::create(['name'=> 'crear generos']);
        Permission::create(['name'=> 'editar generos']);
        Permission::create(['name'=> 'eliminar generos']);

        // roles
        $role = Role::create(['name' => 'admin']);
        // $role->givePermissionTo(Permission::all());

    }
}