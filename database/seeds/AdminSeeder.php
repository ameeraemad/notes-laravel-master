<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Run This after running the permission and roles seeder.
        $admin = \App\Admin::create([
            'name'=>'Super Admin',
            'email'=>'super-admin@note.com',
            'mobile'=>'12341234',
            'password'=>\Illuminate\Support\Facades\Hash::make("1234"),
            'gender'=>'Male',
            'status'=>'Active',
        ]);
        $admin->assignRole('Admin');
    }
}
