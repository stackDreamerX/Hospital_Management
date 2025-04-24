<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WardTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ward_types')->insert([
            [
                'WardTypeID' => 1,
                'TypeName' => 'General Ward',
                'Description' => 'For general medical care and observation',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'WardTypeID' => 2,
                'TypeName' => 'ICU',
                'Description' => 'Intensive Care Unit for critical patients',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'WardTypeID' => 3,
                'TypeName' => 'Pediatric Ward',
                'Description' => 'Specialized care for children',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'WardTypeID' => 4,
                'TypeName' => 'Maternity Ward',
                'Description' => 'For pregnancy and childbirth care',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'WardTypeID' => 5,
                'TypeName' => 'Surgery Ward',
                'Description' => 'Post-operative care and recovery',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'WardTypeID' => 6,
                'TypeName' => 'Psychiatric Ward',
                'Description' => 'For mental health treatment and care',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'WardTypeID' => 7,
                'TypeName' => 'Oncology Ward',
                'Description' => 'Specialized for cancer patients',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
