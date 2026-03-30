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

        // firstOrCreate users permissions
        Permission::firstOrCreate(['name' => 'ver panel usuarios', 'type' => 'usuarios']);
        Permission::firstOrCreate(['name' => 'crear usuarios', 'type' => 'usuarios']);
        Permission::firstOrCreate(['name' => 'editar usuarios', 'type' => 'usuarios']);
        Permission::firstOrCreate(['name' => 'eliminar usuarios', 'type' => 'usuarios']);

        // firstOrCreate rooms permissions
        Permission::firstOrCreate(['name' => 'ver panel consultorios', 'type' => 'consultorios']);
        Permission::firstOrCreate(['name' => 'crear consultorios', 'type' => 'consultorios']);
        Permission::firstOrCreate(['name' => 'editar consultorios', 'type' => 'consultorios']);
        Permission::firstOrCreate(['name' => 'eliminar consultorios', 'type' => 'consultorios']);

        // firstOrCreate permissions 
        Permission::firstOrCreate(['name' => 'ver permisos usuarios', 'type' => 'permisos']);
        Permission::firstOrCreate(['name' => 'editar permisos usuarios', 'type' => 'permisos']);

        // firstOrCreate logs permissions
        Permission::firstOrCreate(['name' => 'ver panel bitácora', 'type' => 'bitácora']);

        // firstOrCreate reasons permissions
        Permission::firstOrCreate(['name' => 'ver panel motivos', 'type' => 'motivos']);
        Permission::firstOrCreate(['name' => 'crear motivos', 'type' => 'motivos']);
        Permission::firstOrCreate(['name' => 'editar motivos', 'type' => 'motivos']);
        Permission::firstOrCreate(['name' => 'eliminar motivos', 'type' => 'motivos']);

        // firstOrCreate areas permissions
        Permission::firstOrCreate(['name' => 'ver panel areas', 'type' => 'áreas']);
        Permission::firstOrCreate(['name' => 'crear areas', 'type' => 'áreas']);
        Permission::firstOrCreate(['name' => 'editar areas', 'type' => 'áreas']);
        Permission::firstOrCreate(['name' => 'eliminar areas', 'type' => 'áreas']);

        // firstOrCreate attention statuses permissions
        Permission::firstOrCreate(['name' => 'ver panel estados de atención', 'type' => 'estados de atención']);
        Permission::firstOrCreate(['name' => 'crear estados de atención', 'type' => 'estados de atención']);
        Permission::firstOrCreate(['name' => 'editar estados de atención', 'type' => 'estados de atención']);
        Permission::firstOrCreate(['name' => 'eliminar estados de atención', 'type' => 'estados de atención']);

        // firstOrCreate grooming statuses permissions
        Permission::firstOrCreate(['name' => 'ver panel estados de grooming', 'type' => 'estados de grooming']);
        Permission::firstOrCreate(['name' => 'crear estados de grooming', 'type' => 'estados de grooming']);
        Permission::firstOrCreate(['name' => 'editar estados de grooming', 'type' => 'estados de grooming']);
        Permission::firstOrCreate(['name' => 'eliminar estados de grooming', 'type' => 'estados de grooming']);

        // firstOrCreate reception types permissions
        Permission::firstOrCreate(['name' => 'ver panel tipos de recepción', 'type' => 'tipos de recepción']);
        Permission::firstOrCreate(['name' => 'crear tipos de recepción', 'type' => 'tipos de recepción']);
        Permission::firstOrCreate(['name' => 'editar tipos de recepción', 'type' => 'tipos de recepción']);
        Permission::firstOrCreate(['name' => 'eliminar tipos de recepción', 'type' => 'tipos de recepción']);

        // firstOrCreate genres permissions
        Permission::firstOrCreate(['name' => 'ver panel generos', 'type' => 'géneros']);
        Permission::firstOrCreate(['name' => 'crear generos', 'type' => 'géneros']);
        Permission::firstOrCreate(['name' => 'editar generos', 'type' => 'géneros']);
        Permission::firstOrCreate(['name' => 'eliminar generos', 'type' => 'géneros']);

        //cretae reproductive status perissions
        Permission::firstOrCreate(['name' => 'ver panel estados reproductivos', 'type' => 'estados reproductivos']);
        Permission::firstOrCreate(['name' => 'crear estados reproductivos', 'type' => 'estados reproductivos']);
        Permission::firstOrCreate(['name' => 'editar estados reproductivos', 'type' => 'estados reproductivos']);
        Permission::firstOrCreate(['name' => 'eliminar estados reproductivos', 'type' => 'estados reproductivos']);
        // firstOrCreate admission types permissions
        Permission::firstOrCreate(['name' => 'ver panel tipos de ingreso', 'type' => 'tipos de ingreso']);
        Permission::firstOrCreate(['name' => 'crear tipos de ingreso', 'type' => 'tipos de ingreso']);
        Permission::firstOrCreate(['name' => 'editar tipos de ingreso', 'type' => 'tipos de ingreso']);
        Permission::firstOrCreate(['name' => 'eliminar tipos de ingreso', 'type' => 'tipos de ingreso']);

        //firstOrCreate family classification permissions
        Permission::firstOrCreate(['name' => 'ver panel clasificaciones familias', 'type' => 'clasificacion de familias']);
        Permission::firstOrCreate(['name' => 'crear clasificaciones familias', 'type' => 'clasificacion de familias']);
        Permission::firstOrCreate(['name' => 'editar clasificaciones familias', 'type' => 'clasificacion de familias']);
        Permission::firstOrCreate(['name' => 'eliminar clasificaciones familias', 'type' => 'clasificacion de familias']);

        //firstOrCreate pet classificaion permissions
        Permission::firstOrCreate(['name' => 'ver panel clasificacion mascotas', 'type' => 'clasificación de mascotas']);
        Permission::firstOrCreate(['name' => 'crear clasificacion mascotas', 'type' => 'clasificación de mascotas']);
        Permission::firstOrCreate(['name' => 'editar clasificacion mascotas', 'type' => 'clasificación de mascotas']);
        Permission::firstOrCreate(['name' => 'eliminar clasificacion mascotas', 'type' => 'clasificación de mascotas']);

        //firstOrCreate shifts permissions
        Permission::firstOrCreate(['name' => 'ver panel turnos', 'type' => 'turnos']);
        Permission::firstOrCreate(['name' => 'crear turnos', 'type' => 'turnos']);
        Permission::firstOrCreate(['name' => 'editar turnos', 'type' => 'turnos']);
        Permission::firstOrCreate(['name' => 'eliminar turnos', 'type' => 'turnos']);

        //firstOrCreate pets statuses permissions
        Permission::firstOrCreate(['name' => 'ver panel estados mascotas', 'type' => 'estado de mascotas']);
        Permission::firstOrCreate(['name' => 'crear estados mascotas', 'type' => 'estado de mascotas']);
        Permission::firstOrCreate(['name' => 'editar estados mascotas', 'type' => 'estado de mascotas']);
        Permission::firstOrCreate(['name' => 'eliminar estados mascotas', 'type' => 'estado de mascotas']);

        //firstOrCreate families permissions
        Permission::firstOrCreate(['name' => 'ver panel familias', 'type' => 'familias']);
        Permission::firstOrCreate(['name' => 'crear familias', 'type' => 'familias']);
        Permission::firstOrCreate(['name' => 'editar familias', 'type' => 'familias']);
        Permission::firstOrCreate(['name' => 'eliminar familias', 'type' => 'familias']);

        //firstOrCreate cover areas permissions
        Permission::firstOrCreate(['name' => 'ver panel areas a cubrir', 'type' => 'áreas a cubrir']);
        Permission::firstOrCreate(['name' => 'crear areas a cubrir', 'type' => 'áreas a cubrir']);
        Permission::firstOrCreate(['name' => 'editar areas a cubrir', 'type' => 'áreas a cubrir']);
        Permission::firstOrCreate(['name' => 'eliminar areas a cubrir', 'type' => 'áreas a cubrir']);

        //firstOrCreate schedules permissions
        Permission::firstOrCreate(['name' => 'ver panel horarios', 'type' => 'horarios']);
        Permission::firstOrCreate(['name' => 'crear horarios', 'type' => 'horarios']);
        Permission::firstOrCreate(['name' => 'editar horarios', 'type' => 'horarios']);
        Permission::firstOrCreate(['name' => 'eliminar horarios', 'type' => 'horarios']);

        //permisos recepcion 
        Permission::firstOrCreate(['name' => 'ver panel recepciones', 'type' => 'recepciones']);
        Permission::firstOrCreate(['name' => 'crear recepciones', 'type' => 'recepciones']);
        Permission::firstOrCreate(['name' => 'editar recepciones', 'type' => 'recepciones']);
        Permission::firstOrCreate(['name' => 'eliminar recepciones', 'type' => 'recepciones']);

        //permisos recepcion 
        Permission::firstOrCreate(['name' => 'ver panel consultas', 'type' => 'consultas']);
        Permission::firstOrCreate(['name' => 'crear consultas', 'type' => 'consultas']);
        Permission::firstOrCreate(['name' => 'editar consultas', 'type' => 'consultas']);
        Permission::firstOrCreate(['name' => 'eliminar consultas', 'type' => 'consultas']);

        //assignments permissions
        Permission::firstOrCreate(['name' => 'ver panel asignaciones', 'type' => 'asignaciones']);

        //prescriptions permissions
        Permission::firstOrCreate(['name' => 'ver panel recetas', 'type' => 'recetas']);
        Permission::firstOrCreate(['name' => 'crear recetas', 'type' => 'recetas']);
        Permission::firstOrCreate(['name' => 'editar recetas', 'type' => 'recetas']);
        Permission::firstOrCreate(['name' => 'eliminar recetas', 'type' => 'recetas']);

        //services
        Permission::firstOrCreate(['name' => 'ver panel servicios', 'type' => 'servicios']);
        Permission::firstOrCreate(['name' => 'crear servicios', 'type' => 'servicios']);
        Permission::firstOrCreate(['name' => 'editar servicios', 'type' => 'servicios']);
        Permission::firstOrCreate(['name' => 'eliminar servicios', 'type' => 'servicios']);

        //services
        Permission::firstOrCreate(['name' => 'ver panel cartilla vacunación', 'type' => 'cartilla vacunación']);
        Permission::firstOrCreate(['name' => 'crear cartilla vacunación', 'type' => 'cartilla vacunación']);
        Permission::firstOrCreate(['name' => 'editar cartilla vacunación', 'type' => 'cartilla vacunación']);
        Permission::firstOrCreate(['name' => 'eliminar cartilla vacunación', 'type' => 'cartilla vacunación']);

        //hospitalizations
        Permission::firstOrCreate(['name' => 'ver panel hospitalizaciones', 'type' => 'hospitalizaciones']);
        Permission::firstOrCreate(['name' => 'crear hospitalizaciones', 'type' => 'hospitalizaciones']);
        Permission::firstOrCreate(['name' => 'editar hospitalizaciones', 'type' => 'hospitalizaciones']);
        Permission::firstOrCreate(['name' => 'eliminar hospitalizaciones', 'type' => 'hospitalizaciones']);

        //red sheets
        Permission::firstOrCreate(['name' => 'ver panel hoja roja', 'type' => 'hoja roja']);
        Permission::firstOrCreate(['name' => 'crear hoja roja', 'type' => 'hoja roja']);
        Permission::firstOrCreate(['name' => 'editar hoja roja', 'type' => 'hoja roja']);
        Permission::firstOrCreate(['name' => 'eliminar hoja roja', 'type' => 'hoja roja']);

        //Surgery
        Permission::firstOrCreate(['name' => 'ver panel cirugías', 'type' => 'cirugías']);
        Permission::firstOrCreate(['name' => 'crear cirugía', 'type' => 'cirugías']);
        Permission::firstOrCreate(['name' => 'editar cirugía', 'type' => 'cirugías']);
        Permission::firstOrCreate(['name' => 'eliminar cirugía', 'type' => 'cirugías']);

        //Surgery schedules
        Permission::firstOrCreate(['name' => 'ver panel horario de cirugías', 'type' => 'cirugías']);
        Permission::firstOrCreate(['name' => 'crear asignación de cirugía', 'type' => 'cirugías']);
        Permission::firstOrCreate(['name' => 'editar asignación de cirugía', 'type' => 'cirugías']);
        Permission::firstOrCreate(['name' => 'eliminar asignación de cirugía', 'type' => 'cirugías']);

        //follow ups
        Permission::firstOrCreate(['name' => 'ver panel seguimientos', 'type' => 'seguimientos']);
        Permission::firstOrCreate(['name' => 'crear seguimientos', 'type' => 'seguimientos']);
        Permission::firstOrCreate(['name' => 'editar seguimientos', 'type' => 'seguimientos']);
        Permission::firstOrCreate(['name' => 'eliminar seguimientos', 'type' => 'seguimientos']);

        //FORMAT TYPES
        Permission::firstOrCreate(['name' => 'Ver panel Tipo de Formatos', 'type' => 'formatos']);
        Permission::firstOrCreate(['name' => 'Crear tipo de formato', 'type' => 'formatos']);
        Permission::firstOrCreate(['name' => 'Editar tipo de formato', 'type' => 'formatos']);
        Permission::firstOrCreate(['name' => 'Eliminar tipo de formato', 'type' => 'formatos']);

        //FORMATS
        Permission::firstOrCreate(['name' => 'Ver panel de Formatos', 'type' => 'formatos']);
        Permission::firstOrCreate(['name' => 'Crear formato', 'type' => 'formatos']);
        Permission::firstOrCreate(['name' => 'Editar formato', 'type' => 'formatos']);
        Permission::firstOrCreate(['name' => 'Eliminar formato', 'type' => 'formatos']);

        //red sheets
        Permission::firstOrCreate(['name' => 'ver panel servicios consultas', 'type' => 'sevicios consultas']);
        Permission::firstOrCreate(['name' => 'crear servicios consultas', 'type' => 'sevicios consultas']);
        Permission::firstOrCreate(['name' => 'editar servicios consultas', 'type' => 'sevicios consultas']);
        Permission::firstOrCreate(['name' => 'eliminar servicios consultas', 'type' => 'sevicios consultas']);

        // surgery budgets permissions
        Permission::firstOrCreate(['name' => 'ver panel presupuestos', 'type' => 'presupuestos']);
        Permission::firstOrCreate(['name' => 'crear presupuestos', 'type' => 'presupuestos']);
        Permission::firstOrCreate(['name' => 'editar presupuestos', 'type' => 'presupuestos']);
        Permission::firstOrCreate(['name' => 'eliminar presupuestos', 'type' => 'presupuestos']);

        //follow ups critics
        Permission::firstOrCreate(['name' => 'ver panel seguimientos de criticos', 'type' => 'seguimientos de criticos']);
        Permission::firstOrCreate(['name' => 'crear seguimientos de criticos', 'type' => 'seguimientos de criticos']);
        Permission::firstOrCreate(['name' => 'editar seguimientos de criticos', 'type' => 'seguimientos de criticos']);
        Permission::firstOrCreate(['name' => 'eliminar seguimientos de criticos', 'type' => 'seguimientos de criticos']);
        //FOLLOWUP INTERN
        Permission::firstOrCreate(['name' => 'Ver panel pase de guardia internos', 'type' => 'pase de guardia']);
        Permission::firstOrCreate(['name' => 'Crear pase de guardia interno', 'type' => 'pase de guardia']);
        Permission::firstOrCreate(['name' => 'Editar pase de guardia interno', 'type' => 'pase de guardia']);
        Permission::firstOrCreate(['name' => 'Eliminar pase de guardia interno', 'type' => 'pase de guardia']);

        //FOLLOWUP SURGICAL
        Permission::firstOrCreate(['name' => 'Ver panel pase de guardia quirúrgicos', 'type' => 'pase de guardia']);
        Permission::firstOrCreate(['name' => 'Crear pase de guardia quirúrgico', 'type' => 'pase de guardia']);
        Permission::firstOrCreate(['name' => 'Editar pase de guardia quirúrgico', 'type' => 'pase de guardia']);
        Permission::firstOrCreate(['name' => 'Eliminar pase de guardia quirúrgico', 'type' => 'pase de guardia']);

        //Grooming Services Permissions
        Permission::firstOrCreate(['name' => 'ver panel grooming', 'type' => 'Grooming']);
        Permission::firstOrCreate(['name' => 'crear grooming', 'type' => 'Grooming']);
        Permission::firstOrCreate(['name' => 'editar grooming', 'type' => 'Grooming']);
        Permission::firstOrCreate(['name' => 'eliminar grooming', 'type' => 'Grooming']);

        //Delivery Services
        Permission::firstOrCreate(['name' => 'ver panel servicios domicilio', 'type' => 'Domicilios']);

        //TAG TYPES
        Permission::firstOrCreate(['name' => 'ver panel tipo de placas para cremación', 'type' => 'cremaciones']);
        Permission::firstOrCreate(['name' => 'crear tipo de placas para cremación', 'type' => 'cremaciones']);
        Permission::firstOrCreate(['name' => 'editar tipo de placas para cremación', 'type' => 'cremaciones']);
        Permission::firstOrCreate(['name' => 'eliminar tipo de placas para cremación', 'type' => 'cremaciones']);

        //CM TYPES
        Permission::firstOrCreate(['name' => 'ver panel C.M', 'type' => 'cremaciones']);
        Permission::firstOrCreate(['name' => 'crear C.M', 'type' => 'cremaciones']);
        Permission::firstOrCreate(['name' => 'editar C.M', 'type' => 'cremaciones']);
        Permission::firstOrCreate(['name' => 'eliminar C.M', 'type' => 'cremaciones']);

        //CREMATIONS
        Permission::firstOrCreate(['name' => 'ver panel cremaciones', 'type' => 'cremaciones']);
        Permission::firstOrCreate(['name' => 'crear cremaciones', 'type' => 'cremaciones']);
        Permission::firstOrCreate(['name' => 'editar cremaciones', 'type' => 'cremaciones']);
        Permission::firstOrCreate(['name' => 'eliminar cremaciones', 'type' => 'cremaciones']);

        //HOTEL
        Permission::firstOrCreate(['name' => 'ver panel hotel', 'type' => 'Hotel']);
        Permission::firstOrCreate(['name' => 'crear pensiones', 'type' => 'Hotel']);
        Permission::firstOrCreate(['name' => 'editar pensiones', 'type' => 'Hotel']);
        Permission::firstOrCreate(['name' => 'eliminar pensiones', 'type' => 'Hotel']);

        //Advance Payments
        Permission::firstOrCreate(['name' => 'ver panel anticipos', 'type' => 'Anticipos']);
        Permission::firstOrCreate(['name' => 'crear anticipos', 'type' => 'Anticipos']);
        Permission::firstOrCreate(['name' => 'editar anticipos', 'type' => 'Anticipos']);
        Permission::firstOrCreate(['name' => 'eliminar anticipos', 'type' => 'Anticipos']);

        //CONTROL DATES
        Permission::firstOrCreate(['name' => 'ver panel citas', 'type' => 'Citas']);
        Permission::firstOrCreate(['name' => 'crear citas', 'type' => 'Citas']);
        Permission::firstOrCreate(['name' => 'editar citas', 'type' => 'Citas']);
        Permission::firstOrCreate(['name' => 'eliminar citas', 'type' => 'Citas']);

        //VOUCHERS
        Permission::firstOrCreate(['name' => 'ver panel vales', 'type' => 'Vales']);
        Permission::firstOrCreate(['name' => 'ver vales', 'type' => 'Vales']);
        Permission::firstOrCreate(['name' => 'crear vales', 'type' => 'Vales']);
        Permission::firstOrCreate(['name' => 'surtir vales', 'type' => 'Vales']);
        Permission::firstOrCreate(['name' => 'rechazar vales', 'type' => 'Vales']);
        Permission::firstOrCreate(['name' => 'cancelar vales', 'type' => 'Vales']);

        // roles and assign each one their respective permisions 
        //Admin
        $role = Role::firstOrCreate(['name' => 'administrador']);
        $role->givePermissionTo(Permission::all());

        //Recepcionist
        $role = Role::firstOrCreate(['name' => 'recepcionista']);
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
        $role = Role::firstOrCreate(['name' => 'medico']);
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
            'ver vales',
            'crear vales',
            'cancelar vales',
        ];
        $role->givePermissionTo($permissionsMVZ);


        //Colaborator
        $role = Role::firstOrCreate(['name' => 'colaborador']);
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

        //Almacenista
        $role = Role::firstOrCreate(['name' => 'almacenista']);
        $permissionsStorekeeper = [
            'ver panel vales',
            'ver vales',
            'surtir vales',
            'rechazar vales',
        ];
        $role->givePermissionTo($permissionsStorekeeper);
    }
}
