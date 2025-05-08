<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

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
        Permission::create(['name' => 'ver panel usuarios', 'type' => 'usuarios']);
        Permission::create(['name' => 'crear usuarios', 'type' => 'usuarios']);
        Permission::create(['name' => 'editar usuarios', 'type' => 'usuarios']);
        Permission::create(['name' => 'eliminar usuarios', 'type' => 'usuarios']);

        // create rooms permissions
        Permission::create(['name' => 'ver panel consultorios', 'type' => 'consultorios']);
        Permission::create(['name' => 'crear consultorios', 'type' => 'consultorios']);
        Permission::create(['name' => 'editar consultorios', 'type' => 'consultorios']);
        Permission::create(['name' => 'eliminar consultorios', 'type' => 'consultorios']);

        // create permissions 
        Permission::create(['name' => 'ver permisos usuarios', 'type' => 'permisos']);
        Permission::create(['name' => 'editar permisos usuarios', 'type' => 'permisos']);

        // create logs permissions
        Permission::create(['name' => 'ver panel bitácora', 'type' => 'bitácora']);

        // create reasons permissions
        Permission::create(['name' => 'ver panel motivos', 'type' => 'motivos']);
        Permission::create(['name' => 'crear motivos', 'type' => 'motivos']);
        Permission::create(['name' => 'editar motivos', 'type' => 'motivos']);
        Permission::create(['name' => 'eliminar motivos', 'type' => 'motivos']);

        // create areas permissions
        Permission::create(['name' => 'ver panel areas', 'type' => 'áreas']);
        Permission::create(['name' => 'crear areas', 'type' => 'áreas']);
        Permission::create(['name' => 'editar areas', 'type' => 'áreas']);
        Permission::create(['name' => 'eliminar areas', 'type' => 'áreas']);

        // create attention statuses permissions
        Permission::create(['name' => 'ver panel estados de atención', 'type' => 'estados de atención']);
        Permission::create(['name' => 'crear estados de atención', 'type' => 'estados de atención']);
        Permission::create(['name' => 'editar estados de atención', 'type' => 'estados de atención']);
        Permission::create(['name' => 'eliminar estados de atención', 'type' => 'estados de atención']);

        // create grooming statuses permissions
        Permission::create(['name' => 'ver panel estados de grooming', 'type' => 'estados de grooming']);
        Permission::create(['name' => 'crear estados de grooming', 'type' => 'estados de grooming']);
        Permission::create(['name' => 'editar estados de grooming', 'type' => 'estados de grooming']);
        Permission::create(['name' => 'eliminar estados de grooming', 'type' => 'estados de grooming']);

        // create reception types permissions
        Permission::create(['name' => 'ver panel tipos de recepción', 'type' => 'tipos de recepción']);
        Permission::create(['name' => 'crear tipos de recepción', 'type' => 'tipos de recepción']);
        Permission::create(['name' => 'editar tipos de recepción', 'type' => 'tipos de recepción']);
        Permission::create(['name' => 'eliminar tipos de recepción', 'type' => 'tipos de recepción']);

        // create genres permissions
        Permission::create(['name' => 'ver panel generos', 'type' => 'géneros']);
        Permission::create(['name' => 'crear generos', 'type' => 'géneros']);
        Permission::create(['name' => 'editar generos', 'type' => 'géneros']);
        Permission::create(['name' => 'eliminar generos', 'type' => 'géneros']);

        //cretae reproductive status perissions
        Permission::create(['name' => 'ver panel estados reproductivos', 'type' => 'estados reproductivos']);
        Permission::create(['name' => 'crear estados reproductivos', 'type' => 'estados reproductivos']);
        Permission::create(['name' => 'editar estados reproductivos', 'type' => 'estados reproductivos']);
        Permission::create(['name' => 'eliminar estados reproductivos', 'type' => 'estados reproductivos']);
        // create admission types permissions
        Permission::create(['name' => 'ver panel tipos de ingreso', 'type' => 'tipos de ingreso']);
        Permission::create(['name' => 'crear tipos de ingreso', 'type' => 'tipos de ingreso']);
        Permission::create(['name' => 'editar tipos de ingreso', 'type' => 'tipos de ingreso']);
        Permission::create(['name' => 'eliminar tipos de ingreso', 'type' => 'tipos de ingreso']);

        //create family classification permissions
        Permission::create(['name' => 'ver panel clasificaciones familias', 'type' => 'clasificacion de familias']);
        Permission::create(['name' => 'crear clasificaciones familias', 'type' => 'clasificacion de familias']);
        Permission::create(['name' => 'editar clasificaciones familias', 'type' => 'clasificacion de familias']);
        Permission::create(['name' => 'eliminar clasificaciones familias', 'type' => 'clasificacion de familias']);

        //create pet classificaion permissions
        Permission::create(['name' => 'ver panel clasificacion mascotas', 'type' => 'clasificación de mascotas']);
        Permission::create(['name' => 'crear clasificacion mascotas', 'type' => 'clasificación de mascotas']);
        Permission::create(['name' => 'editar clasificacion mascotas', 'type' => 'clasificación de mascotas']);
        Permission::create(['name' => 'eliminar clasificacion mascotas', 'type' => 'clasificación de mascotas']);

        //create shifts permissions
        Permission::create(['name' => 'ver panel turnos', 'type' => 'turnos']);
        Permission::create(['name' => 'crear turnos', 'type' => 'turnos']);
        Permission::create(['name' => 'editar turnos', 'type' => 'turnos']);
        Permission::create(['name' => 'eliminar turnos', 'type' => 'turnos']);

        //create pets statuses permissions
        Permission::create(['name' => 'ver panel estados mascotas', 'type' => 'estado de mascotas']);
        Permission::create(['name' => 'crear estados mascotas', 'type' => 'estado de mascotas']);
        Permission::create(['name' => 'editar estados mascotas', 'type' => 'estado de mascotas']);
        Permission::create(['name' => 'eliminar estados mascotas', 'type' => 'estado de mascotas']);

        //create families permissions
        Permission::create(['name' => 'ver panel familias', 'type' => 'familias']);
        Permission::create(['name' => 'crear familias', 'type' => 'familias']);
        Permission::create(['name' => 'editar familias', 'type' => 'familias']);
        Permission::create(['name' => 'eliminar familias', 'type' => 'familias']);

        //create cover areas permissions
        Permission::create(['name' => 'ver panel areas a cubrir', 'type' => 'áreas a cubrir']);
        Permission::create(['name' => 'crear areas a cubrir', 'type' => 'áreas a cubrir']);
        Permission::create(['name' => 'editar areas a cubrir', 'type' => 'áreas a cubrir']);
        Permission::create(['name' => 'eliminar areas a cubrir', 'type' => 'áreas a cubrir']);

        //create schedules permissions
        Permission::create(['name' => 'ver panel horarios', 'type' => 'horarios']);
        Permission::create(['name' => 'crear horarios', 'type' => 'horarios']);
        Permission::create(['name' => 'editar horarios', 'type' => 'horarios']);
        Permission::create(['name' => 'eliminar horarios', 'type' => 'horarios']);

        //permisos recepcion 
        Permission::create(['name' => 'ver panel recepciones', 'type' => 'recepciones']);
        Permission::create(['name' => 'crear recepciones', 'type' => 'recepciones']);
        Permission::create(['name' => 'editar recepciones', 'type' => 'recepciones']);
        Permission::create(['name' => 'eliminar recepciones', 'type' => 'recepciones']);

        //permisos recepcion 
        Permission::create(['name' => 'ver panel consultas', 'type' => 'consultas']);
        Permission::create(['name' => 'crear consultas', 'type' => 'consultas']);
        Permission::create(['name' => 'editar consultas', 'type' => 'consultas']);
        Permission::create(['name' => 'eliminar consultas', 'type' => 'consultas']);

        //assignments permissions
        Permission::create(['name' => 'ver panel asignaciones', 'type' => 'asignaciones']);

        //prescriptions permissions
        Permission::create(['name' => 'ver panel recetas', 'type' => 'recetas']);
        Permission::create(['name' => 'crear recetas', 'type' => 'recetas']);
        Permission::create(['name' => 'editar recetas', 'type' => 'recetas']);
        Permission::create(['name' => 'eliminar recetas', 'type' => 'recetas']);

        //services
        Permission::create(['name' => 'ver panel servicios', 'type' => 'servicios']);
        Permission::create(['name' => 'crear servicios', 'type' => 'servicios']);
        Permission::create(['name' => 'editar servicios', 'type' => 'servicios']);
        Permission::create(['name' => 'eliminar servicios', 'type' => 'servicios']);

        //services
        Permission::create(['name' => 'ver panel cartilla vacunación', 'type' => 'cartilla vacunación']);
        Permission::create(['name' => 'crear cartilla vacunación', 'type' => 'cartilla vacunación']);
        Permission::create(['name' => 'editar cartilla vacunación', 'type' => 'cartilla vacunación']);
        Permission::create(['name' => 'eliminar cartilla vacunación', 'type' => 'cartilla vacunación']);

        //hospitalizations
        Permission::create(['name' => 'ver panel hospitalizaciones', 'type' => 'hospitalizaciones']);
        Permission::create(['name' => 'crear hospitalizaciones', 'type' => 'hospitalizaciones']);
        Permission::create(['name' => 'editar hospitalizaciones', 'type' => 'hospitalizaciones']);
        Permission::create(['name' => 'eliminar hospitalizaciones', 'type' => 'hospitalizaciones']);

        //red sheets
        Permission::create(['name' => 'ver panel hoja roja', 'type' => 'hoja roja']);
        Permission::create(['name' => 'crear hoja roja', 'type' => 'hoja roja']);
        Permission::create(['name' => 'editar hoja roja', 'type' => 'hoja roja']);
        Permission::create(['name' => 'eliminar hoja roja', 'type' => 'hoja roja']);

        //Surgery
        Permission::create(['name' => 'ver panel cirugías', 'type' => 'cirugías']);
        Permission::create(['name' => 'crear cirugía', 'type' => 'cirugías']);
        Permission::create(['name' => 'editar cirugía', 'type' => 'cirugías']);
        Permission::create(['name' => 'eliminar cirugía', 'type' => 'cirugías']);

        //Surgery schedules
        Permission::create(['name' => 'ver panel horario de cirugías', 'type' => 'cirugías']);
        Permission::create(['name' => 'crear asignación de cirugía', 'type' => 'cirugías']);
        Permission::create(['name' => 'editar asignación de cirugía', 'type' => 'cirugías']);
        Permission::create(['name' => 'eliminar asignación de cirugía', 'type' => 'cirugías']);

        //follow ups
        Permission::create(['name' => 'ver panel seguimientos', 'type' => 'seguimientos']);
        Permission::create(['name' => 'crear seguimientos', 'type' => 'seguimientos']);
        Permission::create(['name' => 'editar seguimientos', 'type' => 'seguimientos']);
        Permission::create(['name' => 'eliminar seguimientos', 'type' => 'seguimientos']);

        //FORMAT TYPES
        Permission::create(['name' => 'Ver panel Tipo de Formatos', 'type' => 'formatos']);
        Permission::create(['name' => 'Crear tipo de formato', 'type' => 'formatos']);
        Permission::create(['name' => 'Editar tipo de formato', 'type' => 'formatos']);
        Permission::create(['name' => 'Eliminar tipo de formato', 'type' => 'formatos']);

        //FORMATS
        Permission::create(['name' => 'Ver panel de Formatos', 'type' => 'formatos']);
        Permission::create(['name' => 'Crear formato', 'type' => 'formatos']);
        Permission::create(['name' => 'Editar formato', 'type' => 'formatos']);
        Permission::create(['name' => 'Eliminar formato', 'type' => 'formatos']);

        //red sheets
        Permission::create(['name' => 'ver panel servicios consultas', 'type' => 'sevicios consultas']);
        Permission::create(['name' => 'crear servicios consultas', 'type' => 'sevicios consultas']);
        Permission::create(['name' => 'editar servicios consultas', 'type' => 'sevicios consultas']);
        Permission::create(['name' => 'eliminar servicios consultas', 'type' => 'sevicios consultas']);

        // surgery budgets permissions
        Permission::create(['name' => 'ver panel presupuestos', 'type' => 'presupuestos']);
        Permission::create(['name' => 'crear presupuestos', 'type' => 'presupuestos']);
        Permission::create(['name' => 'editar presupuestos', 'type' => 'presupuestos']);
        Permission::create(['name' => 'eliminar presupuestos', 'type' => 'presupuestos']);

        //follow ups critics
        Permission::create(['name' => 'ver panel seguimientos de criticos', 'type' => 'seguimientos de criticos']);
        Permission::create(['name' => 'crear seguimientos de criticos', 'type' => 'seguimientos de criticos']);
        Permission::create(['name' => 'editar seguimientos de criticos', 'type' => 'seguimientos de criticos']);
        Permission::create(['name' => 'eliminar seguimientos de criticos', 'type' => 'seguimientos de criticos']);
        //FOLLOWUP INTERN
        Permission::create(['name' => 'Ver panel pase de guardia internos', 'type' => 'pase de guardia']);
        Permission::create(['name' => 'Crear pase de guardia interno', 'type' => 'pase de guardia']);
        Permission::create(['name' => 'Editar pase de guardia interno', 'type' => 'pase de guardia']);
        Permission::create(['name' => 'Eliminar pase de guardia interno', 'type' => 'pase de guardia']);

        //FOLLOWUP SURGICAL
        Permission::create(['name' => 'Ver panel pase de guardia quirúrgicos', 'type' => 'pase de guardia']);
        Permission::create(['name' => 'Crear pase de guardia quirúrgico', 'type' => 'pase de guardia']);
        Permission::create(['name' => 'Editar pase de guardia quirúrgico', 'type' => 'pase de guardia']);
        Permission::create(['name' => 'Eliminar pase de guardia quirúrgico', 'type' => 'pase de guardia']);

        //Grooming Services Permissions
        Permission::create(['name' => 'ver panel grooming', 'type' => 'Grooming']);
        Permission::create(['name' => 'crear grooming', 'type' => 'Grooming']);
        Permission::create(['name' => 'editar grooming', 'type' => 'Grooming']);
        Permission::create(['name' => 'eliminar grooming', 'type' => 'Grooming']);

        //Delivery Services
        Permission::create(['name' => 'ver panel servicios domicilio', 'type' => 'Domicilios']);

        //TAG TYPES
        Permission::create(['name' => 'ver panel tipo de placas para cremación', 'type' => 'cremaciones']);
        Permission::create(['name' => 'crear tipo de placas para cremación', 'type' => 'cremaciones']);
        Permission::create(['name' => 'editar tipo de placas para cremación', 'type' => 'cremaciones']);
        Permission::create(['name' => 'eliminar tipo de placas para cremación', 'type' => 'cremaciones']);

        //CM TYPES
        Permission::create(['name' => 'ver panel C.M', 'type' => 'cremaciones']);
        Permission::create(['name' => 'crear C.M', 'type' => 'cremaciones']);
        Permission::create(['name' => 'editar C.M', 'type' => 'cremaciones']);
        Permission::create(['name' => 'eliminar C.M', 'type' => 'cremaciones']);

        //CREMATIONS
        Permission::create(['name' => 'ver panel cremaciones', 'type' => 'cremaciones']);
        Permission::create(['name' => 'crear cremaciones', 'type' => 'cremaciones']);
        Permission::create(['name' => 'editar cremaciones', 'type' => 'cremaciones']);
        Permission::create(['name' => 'eliminar cremaciones', 'type' => 'cremaciones']);

        //HOTEL
        Permission::create(['name' => 'ver panel hotel', 'type' => 'Hotel']);
        Permission::create(['name' => 'crear pensiones', 'type' => 'Hotel']);
        Permission::create(['name' => 'editar pensiones', 'type' => 'Hotel']);
        Permission::create(['name' => 'eliminar pensiones', 'type' => 'Hotel']);

        //Advance Payments
        Permission::create(['name' => 'ver panel anticipos', 'type' => 'Anticipos']);
        Permission::create(['name' => 'crear anticipos', 'type' => 'Anticipos']);
        Permission::create(['name' => 'editar anticipos', 'type' => 'Anticipos']);
        Permission::create(['name' => 'eliminar anticipos', 'type' => 'Anticipos']);

        //CONTROL DATES
        Permission::create(['name' => 'ver panel citas', 'type' => 'Citas']);
        Permission::create(['name' => 'crear citas', 'type' => 'Citas']);
        Permission::create(['name' => 'editar citas', 'type' => 'Citas']);
        Permission::create(['name' => 'eliminar citas', 'type' => 'Citas']);


        // roles and assign each one their respective permisions 
        //Admin
        $role = Role::create(['name' => 'administrador']);
        $role->givePermissionTo(Permission::all());

        //Recepcionist
        $role = Role::create(['name' => 'recepcionista']);
        $permissionsReceptionist = [
            'ver panel usuarios',
            'ver panel consultorios',
            'ver panel bitácora',
            'ver panel motivos',
            'ver panel areas',
            'ver panel estados de atención',
            'ver panel estados de grooming',
            'ver panel tipos de recepción',
            'ver panel generos',
            'ver panel estados reproductivos',
            'ver panel tipos de ingreso',
            'ver panel clasificaciones familias',
            'ver panel clasificacion mascotas',
            'ver panel turnos',
            'ver panel estados mascotas',
            'ver panel familias',
            'crear familias',
            'editar familias',
            'eliminar familias',
            'ver panel areas a cubrir',
            'ver panel horarios',
            'ver panel recepciones',
            'crear recepciones',
            'editar recepciones',
            'eliminar recepciones',
            'ver panel consultas',
            'ver panel asignaciones',
            'ver panel recetas',
            'crear recetas',
            'editar recetas',
            'eliminar recetas',
            'ver panel servicios',
            'crear servicios',
            'editar servicios',
            'eliminar servicios',
            'ver panel cartilla vacunación',
            'ver panel hospitalizaciones',
            'crear hospitalizaciones',
            'editar hospitalizaciones',
            'eliminar hospitalizaciones',
            'ver panel hoja roja',
            'crear hoja roja',
            'editar hoja roja',
            'eliminar hoja roja',
            'ver panel cirugías',
            'crear cirugía',
            'editar cirugía',
            'eliminar cirugía',
            'ver panel horario de cirugías',
            'crear asignación de cirugía',
            'editar asignación de cirugía',
            'eliminar asignación de cirugía',
            'ver panel seguimientos',
            'Ver panel Tipo de Formatos',
            'Ver panel de Formatos',
            'Crear formato',
            'Editar formato',
            'Eliminar formato',
            'ver panel servicios consultas',
            'ver panel presupuestos',
            'crear presupuestos',
            'editar presupuestos',
            'eliminar presupuestos',
            'ver panel seguimientos de criticos',
            'Ver panel pase de guardia internos',
            'Ver panel pase de guardia quirúrgicos',
            'ver panel grooming',
            'crear grooming',
            'editar grooming',
            'eliminar grooming',
            'ver panel servicios domicilio',
            'ver panel tipo de placas para cremación',
            'ver panel C.M',
            'crear C.M',
            'editar C.M',
            'eliminar C.M',
            'ver panel cremaciones',
            'crear cremaciones',
            'editar cremaciones',
            'eliminar cremaciones',
            'ver panel hotel',
            'crear pensiones',
            'editar pensiones',
            'eliminar pensiones',
            'ver panel anticipos',
            'crear anticipos',
            'editar anticipos',
            'eliminar anticipos',
            'ver panel citas',
            'crear citas',
            'editar citas',
            'eliminar citas',
        ];
        $role->givePermissionTo($permissionsReceptionist);

        //MVZ
        $role = Role::create(['name' => 'medico']);
        $permissionsMVZ = [
            'ver panel usuarios',
            'ver panel consultorios',
            'ver panel bitácora',
            'ver panel motivos',
            'ver panel estados de atención',
            'ver panel clasificaciones familias',
            'ver panel clasificacion mascotas',
            'ver panel estados mascotas',
            'ver panel familias',
            'ver panel horarios',
            'ver panel recepciones',
            'ver panel consultas',
            'crear consultas',
            'editar consultas',
            'eliminar consultas',
            'ver panel asignaciones',
            'ver panel recetas',
            'crear recetas',
            'editar recetas',
            'eliminar recetas',
            'ver panel servicios',
            'crear servicios',
            'editar servicios',
            'eliminar servicios',
            'ver panel cartilla vacunación',
            'crear cartilla vacunación',
            'editar cartilla vacunación',
            'eliminar cartilla vacunación',
            'ver panel hospitalizaciones',
            'crear hospitalizaciones',
            'editar hospitalizaciones',
            'eliminar hospitalizaciones',
            'ver panel hoja roja',
            'crear hoja roja',
            'editar hoja roja',
            'eliminar hoja roja',
            'ver panel cirugías',
            'crear cirugía',
            'editar cirugía',
            'eliminar cirugía',
            'ver panel horario de cirugías',
            'crear asignación de cirugía',
            'editar asignación de cirugía',
            'eliminar asignación de cirugía',
            'ver panel seguimientos',
            'crear seguimientos',
            'editar seguimientos',
            'eliminar seguimientos',
            'Ver panel de Formatos',
            'Crear formato',
            'Editar formato',
            'Eliminar formato',
            'ver panel servicios consultas',
            'crear servicios consultas',
            'editar servicios consultas',
            'eliminar servicios consultas',
            'ver panel presupuestos',
            'crear presupuestos',
            'editar presupuestos',
            'eliminar presupuestos',
            'ver panel seguimientos de criticos',
            'crear seguimientos de criticos',
            'editar seguimientos de criticos',
            'eliminar seguimientos de criticos',
            'Ver panel pase de guardia internos',
            'Crear pase de guardia interno',
            'Editar pase de guardia interno',
            'Eliminar pase de guardia interno',
            'Ver panel pase de guardia quirúrgicos',
            'Crear pase de guardia quirúrgico',
            'Editar pase de guardia quirúrgico',
            'Eliminar pase de guardia quirúrgico',
            'ver panel grooming',
            'ver panel citas',
        ];
        $role->givePermissionTo($permissionsMVZ);


        //Colaborator
        $role = Role::create(['name' => 'colaborador']);
        $permissionsCollab = [
            'ver panel estados de atención',
            'ver panel estados de grooming',
            'ver panel clasificacion mascotas',
            'ver panel horarios',
            'ver panel asignaciones',
            'ver panel cartilla vacunación',
            'Ver panel de Formatos',
            'ver panel grooming',
            'ver panel servicios domicilio',
            'ver panel C.M',
            'ver panel cremaciones',
            'ver panel hotel',
            'ver panel citas',
        ];
        $role->givePermissionTo($permissionsCollab);
    }
}
