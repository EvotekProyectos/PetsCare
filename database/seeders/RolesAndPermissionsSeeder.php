<?php

namespace Database\Seeders;

use App\Models\Service;
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
        Permission::create(['name' => 'ver panel usuarios']);
        Permission::create(['name' => 'crear usuarios']);
        Permission::create(['name' => 'editar usuarios']);
        Permission::create(['name' => 'eliminar usuarios']);

        // create rooms permissions
        Permission::create(['name' => 'ver panel consultorios']);
        Permission::create(['name' => 'crear consultorios']);
        Permission::create(['name' => 'editar consultorios']);
        Permission::create(['name' => 'eliminar consultorios']);

        // create permissions 
        Permission::create(['name' => 'ver permisos usuarios']);
        Permission::create(['name' => 'editar permisos usuarios']);

        // create logs permissions
        Permission::create(['name' => 'ver panel bitácora']);

        // create reasons permissions
        Permission::create(['name' => 'ver panel motivos']);
        Permission::create(['name' => 'crear motivos']);
        Permission::create(['name' => 'editar motivos']);
        Permission::create(['name' => 'eliminar motivos']);

        // create areas permissions
        Permission::create(['name' => 'ver panel areas']);
        Permission::create(['name' => 'crear areas']);
        Permission::create(['name' => 'editar areas']);
        Permission::create(['name' => 'eliminar areas']);

        // create attention statuses permissions
        Permission::create(['name' => 'ver panel estados de atención']);
        Permission::create(['name' => 'crear estados de atención']);
        Permission::create(['name' => 'editar estados de atención']);
        Permission::create(['name' => 'eliminar estados de atención']);

        // create reception types permissions
        Permission::create(['name' => 'ver panel tipos de recepción']);
        Permission::create(['name' => 'crear tipos de recepción']);
        Permission::create(['name' => 'editar tipos de recepción']);
        Permission::create(['name' => 'eliminar tipos de recepción']);

        // create genres permissions
        Permission::create(['name' => 'ver panel generos']);
        Permission::create(['name' => 'crear generos']);
        Permission::create(['name' => 'editar generos']);
        Permission::create(['name' => 'eliminar generos']);

        //cretae reproductive status perissions
        Permission::create(['name' => 'ver panel estados reproductivos']);
        Permission::create(['name' => 'crear estados reproductivos']);
        Permission::create(['name' => 'editar estados reproductivos']);
        Permission::create(['name' => 'eliminar estados reproductivos']);
        // create admission types permissions
        Permission::create(['name' => 'ver panel tipos de ingreso']);
        Permission::create(['name' => 'crear tipos de ingreso']);
        Permission::create(['name' => 'editar tipos de ingreso']);
        Permission::create(['name' => 'eliminar tipos de ingreso']);

        //create family classification permissions
        Permission::create(['name' => 'ver panel clasificaciones familias']);
        Permission::create(['name' => 'crear clasificaciones familias']);
        Permission::create(['name' => 'editar clasificaciones familias']);
        Permission::create(['name' => 'eliminar clasificaciones familias']);

        //create pet classificaion permissions
        Permission::create(['name' => 'ver panel clasificacion mascotas']);
        Permission::create(['name' => 'crear clasificacion mascotas']);
        Permission::create(['name' => 'editar clasificacion mascotas']);
        Permission::create(['name' => 'eliminar clasificacion mascotas']);

        //create shifts permissions
        Permission::create(['name' => 'ver panel turnos']);
        Permission::create(['name' => 'crear turnos']);
        Permission::create(['name' => 'editar turnos']);
        Permission::create(['name' => 'eliminar turnos']);

        //create pets statuses permissions
        Permission::create(['name' => 'ver panel estados mascotas']);
        Permission::create(['name' => 'crear estados mascotas']);
        Permission::create(['name' => 'editar estados mascotas']);
        Permission::create(['name' => 'eliminar estados mascotas']);

        //create families permissions
        Permission::create(['name' => 'ver panel familias']);
        Permission::create(['name' => 'crear familias']);
        Permission::create(['name' => 'editar familias']);
        Permission::create(['name' => 'eliminar familias']);

        //create cover areas permissions
        Permission::create(['name' => 'ver panel areas a cubrir']);
        Permission::create(['name' => 'crear areas a cubrir']);
        Permission::create(['name' => 'editar areas a cubrir']);
        Permission::create(['name' => 'eliminar areas a cubrir']);

        //create schedules permissions
        Permission::create(['name' => 'ver panel horarios']);
        Permission::create(['name' => 'crear horarios']);
        Permission::create(['name' => 'editar horarios']);
        Permission::create(['name' => 'eliminar horarios']);

        //permisos recepcion 
        Permission::create(['name' => 'ver panel recepciones']);
        Permission::create(['name' => 'crear recepciones']);
        Permission::create(['name' => 'editar recepciones']);
        Permission::create(['name' => 'eliminar recepciones']);

        //permisos recepcion 
        Permission::create(['name' => 'ver panel consultas']);
        Permission::create(['name' => 'crear consultas']);
        Permission::create(['name' => 'editar consultas']);
        Permission::create(['name' => 'eliminar consultas']);

        // roles
        $role = Role::create(['name' => 'admin']);
        // $role->givePermissionTo(Permission::all());

        //assignments permissions
        Permission::create(['name' => 'ver panel asignaciones']);

        //prescriptions permissions
        Permission::create(['name' => 'ver panel recetas']);
        Permission::create(['name' => 'crear recetas']);
        Permission::create(['name' => 'editar recetas']);
        Permission::create(['name' => 'eliminar recetas']);

        ////services
        Permission::create(['name' => 'ver panel servicios']);
        Permission::create(['name' => 'crear servicios']);
        Permission::create(['name' => 'editar servicios']);
        Permission::create(['name' => 'eliminar servicios']);

        ////services
        Permission::create(['name' => 'ver panel cartilla vacunación']);
        Permission::create(['name' => 'crear cartilla vacunación']);
        Permission::create(['name' => 'editar cartilla vacunación']);
        Permission::create(['name' => 'eliminar cartilla vacunación']);

        ////hospitalizations
        Permission::create(['name' => 'ver panel hospitalizaciones']);
        Permission::create(['name' => 'crear hospitalizaciones']);
        Permission::create(['name' => 'editar hospitalizaciones']);
        Permission::create(['name' => 'eliminar hospitalizaciones']);

        ////red sheets
        Permission::create(['name' => 'ver panel hoja roja']);
        Permission::create(['name' => 'crear hoja roja']);
        Permission::create(['name' => 'editar hoja roja']);
        Permission::create(['name' => 'eliminar hoja roja']);
    }
}
