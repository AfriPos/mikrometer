<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class MikrotikPPPoEPermissionSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            // Define CRUD permissions for routers
            $this->createPermission('create routers');
            $this->createPermission('read routers');
            $this->createPermission('update routers');
            $this->createPermission('delete routers');

            // Define CRUD permissions for PPPoE services
            $this->createPermission('create pppoe');
            $this->createPermission('read pppoe');
            $this->createPermission('update pppoe');
            $this->createPermission('delete pppoe');

            // Define CRUD permissions for customers
            $this->createPermission('create customers');
            $this->createPermission('read customers');
            $this->createPermission('update customers');
            $this->createPermission('delete customers');

            // Define CRUD permissions for services
            $this->createPermission('create services');
            $this->createPermission('read services');
            $this->createPermission('update services');
            $this->createPermission('delete services');

            // Define CRUD permissions for payments
            $this->createPermission('create payments');
            $this->createPermission('read payments');
            $this->createPermission('update payments');
            $this->createPermission('delete payments');

            // Define CRUD permissions for roles
            $this->createPermission('read roles');
            $this->createPermission('editpermissions roles');

            // Define CRUD permissions for users
            $this->createPermission('create users');
            $this->createPermission('read users');
            $this->createPermission('update users');
            $this->createPermission('delete users');

            // Define custom permissions
            $this->createPermission('view dashboard');
            $this->createPermission('manage profile');
            $this->createPermission('manage pools');
            $this->createPermission('fetch interfaces');
            $this->createPermission('view active sessions');
            $this->createPermission('manage administration');

            // Assign permissions to roles
            $adminRole = $this->createRole('Administrator');
            $adminRole->syncPermissions(Permission::all());

            $managerRole = $this->createRole('Manager');
            $managerRole->syncPermissions(Permission::where('name', 'not like', '%delete%')->get());

            $technicianRole = $this->createRole('Technician');
            $technicianRole->syncPermissions([
                'read routers',
                'read pppoe',
                'read customers',
                'read services',
                'fetch interfaces',
                'view active sessions'
            ]);

            $accountantRole = $this->createRole('Accountant');
            $accountantRole->syncPermissions([
                'read customers',
                'read services',
                'create payments',
                'read payments',
                'update payments'
            ]);
        });
    }

    private function createPermission($name)
    {
        if (Permission::where('name', $name)->doesntExist()) {
            Permission::create(['name' => $name]);
        }
    }

    private function createRole($name)
    {
        return Role::firstOrCreate(['name' => $name]);
    }
}
