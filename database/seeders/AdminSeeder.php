<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create(['name'=>'SuperAdmin','role'=>'Admin','email'=>'admin@test.com','password'=>Hash::make('password')]);
        Artisan::call('passport:install');
    }
}
