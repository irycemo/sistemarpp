<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role1 = Role::create(['name' => 'Administrador']);
        $role2 = Role::create(['name' => 'Supervisor Copias']);
        $role3 = Role::create(['name' => 'Certificador']);
        $role4 = Role::create(['name' => 'Fotocopiador']);
        $role5 = Role::create(['name' => 'Director']);
        $role5 = Role::create(['name' => 'Consulta']);

        Permission::create(['name' => 'Lista de roles', 'area' => 'Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear rol', 'area' => 'Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar rol', 'area' => 'Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar rol', 'area' => 'Roles'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de permisos', 'area' => 'Permisos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear permiso', 'area' => 'Permisos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar permiso', 'area' => 'Permisos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar permiso', 'area' => 'Permisos'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de usuarios', 'area' => 'Usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear usuario', 'area' => 'Usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar usuario', 'area' => 'Usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar usuario', 'area' => 'Usuarios'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de distritos', 'area' => 'Distritos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear distrito', 'area' => 'Distritos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar distrito', 'area' => 'Distritos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar distrito', 'area' => 'Distritos'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de municipios', 'area' => 'Municipios'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear municipio', 'area' => 'Municipios'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar municipio', 'area' => 'Municipios'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar municipio', 'area' => 'Municipios'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de tenencias', 'area' => 'Tenencias'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear tenencia', 'area' => 'Tenencias'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar tenencia', 'area' => 'Tenencias'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar tenencia', 'area' => 'Tenencias'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de ranchos', 'area' => 'Ranchos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear rancho', 'area' => 'Ranchos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar rancho', 'area' => 'Ranchos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar rancho', 'area' => 'Ranchos'])->syncRoles([$role1]);

        Permission::create(['name' => 'Auditoria', 'area' => 'Auditoria'])->syncRoles([$role1]);

        Permission::create(['name' => 'Logs', 'area' => 'Logs'])->syncRoles([$role1]);

        Permission::create(['name' => 'Área Certificaciones', 'area' => 'Certificaciones'])->syncRoles([$role1, $role3, $role2]);
        Permission::create(['name' => 'Copias Simples', 'area' => 'Certificaciones'])->syncRoles([$role1, $role3, $role2]);
        Permission::create(['name' => 'Copias Certificadas', 'area' => 'Certificaciones'])->syncRoles([$role1, $role3, $role2]);
        Permission::create(['name' => 'Reimprimir documento', 'area' => 'Certificaciones'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'Finalizar copias simples', 'area' => 'Certificaciones'])->syncRoles([$role1, $role3, $role2]);
        Permission::create(['name' => 'Consultas', 'area' => 'Certificaciones'])->syncRoles([$role1]);
        Permission::create(['name' => 'Rechazar copias certificadas', 'area' => 'Certificaciones'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'Finalizar copias certificadas', 'area' => 'Certificaciones'])->syncRoles([$role1, $role3, $role2]);
        Permission::create(['name' => 'Consultas', 'area' => 'Certificaciones'])->syncRoles([$role1, $role5]);
        Permission::create(['name' => 'Finalizar consulta', 'area' => 'Certificaciones'])->syncRoles([$role1, $role5]);

    }

}
