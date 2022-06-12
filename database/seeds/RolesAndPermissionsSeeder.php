<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Permission::create(['name' => 'create-admin', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'read-admins', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'update-admin', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'delete-admin', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);

        Permission::create(['name' => 'create-category', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'read-categories', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'update-category', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'delete-category', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);

        Permission::create(['name' => 'create-note', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'read-note', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'update-note', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'delete-note', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);

        Permission::create(['name' => 'create-student', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'read-students', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'update-student', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'delete-student', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);

        Permission::create(['name' => 'create-user', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'read-users', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'update-user', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'delete-user', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);

        Permission::create(['name' => 'create-role', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'read-roles', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'update-role', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'delete-role', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);

        Permission::create(['name' => 'create-permission', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'read-permissions', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'update-permission', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'delete-permission', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);

        Permission::create(['name' => 'create-notification', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'read-notification', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'update-notification', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'delete-notification', 'guard_name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);

        //------------

        Permission::create(['name' => 'create-note', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'read-note', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'update-note', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'delete-note', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);

        Permission::create(['name' => 'create-category', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'read-category', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'update-category', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'delete-category', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);

        Permission::create(['name' => 'create-user', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'read-users', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'update-user', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'delete-user', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);

        Permission::create(['name' => 'create-notification', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'read-notification', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'update-notification', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);
        Permission::create(['name' => 'delete-notification', 'guard_name' => 'student', 'created_at' => now(), 'updated_at' => now()]);
    }
}
