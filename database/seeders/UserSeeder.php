<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'branch_id' => 1,
                'role' => 'admin',
                'email' => 'admin@admin.com',
                'phone' => '639072203267',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Coordinator',
                'branch_id' => 2,
                'role' => 'coordinator',
                'email' => 'coordinator@admin.com',
                'phone' => '639558076389',
                'password' => Hash::make('password'),
            ],
        ]);
    }
}
