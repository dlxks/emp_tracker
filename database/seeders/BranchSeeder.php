<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('branches')->insert([
            [
                'branch_name' =>  'MAIN',
                'branch_desc' => 'Cavite State University - Admin',
            ],
            [
                'branch_name' =>  'CEIT',
                'branch_desc' => 'College of Engineering and Information Technology',
            ],
            [
                'branch_name' =>  'CON',
                'branch_desc' => 'College of Nursing',
            ],
            [
                'branch_name' =>  'CED',
                'branch_desc' => 'College of Education',
            ],
            [
                'branch_name' =>  'CAS',
                'branch_desc' => 'College of Arts and Sciences',
            ],
            [
                'branch_name' =>  'CAFENR',
                'branch_desc' => 'College of Agriculture, Food, Environment, and Natural Resources',
            ],
            [
                'branch_name' =>  'CEMDS',
                'branch_desc' => 'College of Economic Management and Development Studies Technology',
            ],
            [
                'branch_name' =>  'CCJ',
                'branch_desc' => 'College of Criminal Justice',
            ],
            [
                'branch_name' =>  'CSPEAR',
                'branch_desc' => 'College of Sports, Physical Education, and Recreation',
            ],
            [
                'branch_name' =>  'CVMBS',
                'branch_desc' => 'College of Veterenary Medicine, and Biomedical Sciences',
            ],
        ]);
    }
}
