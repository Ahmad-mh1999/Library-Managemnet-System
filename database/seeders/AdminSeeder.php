<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $admin = User::create([
            'name' => 'owner',
            'email' => 'ahmed@gmail.com',
            'password' => Hash::make('admin1234'),
        ]);
        // in admin seeder we put the admin role first so
        // the id of admin role is 1
        $admin->roles()->attach(1);
    }
}
