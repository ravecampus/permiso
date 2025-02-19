<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employment_classification')->delete();
        DB::table('employment_classification')->insert([
            'id' => 1,
            'title' => 'LEVEL II ADMINISTRATORS',
            'sub_title' => 'FORM 114 - APPLICATION FOR LEAVE',
        ]);

        DB::table('employment_classification')->insert([
            'id' => 2,
            'title' => 'LEVEL III ADMINISTRATORS',
            'sub_title' => 'FORM 114 - APPLICATION FOR LEAVE',
        ]);

        DB::table('employment_classification')->insert([
            'id' => 3,
            'title' => 'LEVEL III NON-ACADEMIC ADMINISTRATORS',
            'sub_title' => 'FORM 114 - APPLICATION FOR LEAVE',
        ]);

        DB::table('employment_classification')->insert([
            'id' => 4,
            'title' => 'LEVEL IV ADMINISTRATORS',
            'sub_title' => 'FORM 114B - APPLICATION FOR LEAVE',
        ]);

        DB::table('employment_classification')->insert([
            'id' => 5,
            'title' => 'NON-TEACHING PERSONNE',
            'sub_title' => 'FORM 114 - APPLICATION FOR LEAVE',
        ]);
        DB::table('employment_classification')->insert([
            'id' => 6,
            'title' => 'COLLEGE / IBED FACULTY',
            'sub_title' => 'FORM 114 - APPLICATION FOR LEAVE',
        ]);

        DB::table('leave')->delete();
        DB::table('leave')->insert([
            'id' => 1,
            'description' => 'OFFICIAL',
        ]);
        DB::table('leave')->insert([
            'id' => 2,
            'description' => 'MATERNITY',
        ]);
        DB::table('leave')->insert([
            'id' => 3,
            'description' => 'ABSENCE',
        ]);
        DB::table('leave')->insert([
            'id' => 4,
            'description' => 'SICK',
        ]);
        DB::table('leave')->insert([
            'id' => 5,
            'description' => 'VACATION',
        ]);
        DB::table('leave')->insert([
            'id' => 6,
            'description' => 'EMERGENCY',
        ]);
        DB::table('leave')->insert([
            'id' => 7,
            'description' => 'STUDY',
        ]);
        DB::table('leave')->insert([
            'id' => 8,
            'description' => 'SABBATICAL',
        ]);

        DB::table('leave')->insert([
            'id' => 9,
            'description' => 'OTHERS',
        ]);
    }
}
