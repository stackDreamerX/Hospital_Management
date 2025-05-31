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
                'TypeName' => 'Phòng Điều Trị Thường',
                'Description' => 'Dành cho bệnh nhân cần chăm sóc và điều trị thông thường',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'WardTypeID' => 2,
                'TypeName' => 'Phòng Hồi Sức Tích Cực (ICU)',
                'Description' => 'Dành cho bệnh nhân nặng cần theo dõi và chăm sóc đặc biệt 24/7',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'WardTypeID' => 3,
                'TypeName' => 'Khoa Nhi',
                'Description' => 'Chăm sóc đặc biệt dành cho trẻ em và trẻ sơ sinh',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'WardTypeID' => 4,
                'TypeName' => 'Khoa Sản',
                'Description' => 'Dành cho sản phụ và chăm sóc sau sinh',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'WardTypeID' => 5,
                'TypeName' => 'Khoa Phẫu Thuật',
                'Description' => 'Chăm sóc hậu phẫu và phục hồi sau mổ',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'WardTypeID' => 6,
                'TypeName' => 'Khoa Tâm Thần',
                'Description' => 'Điều trị và chăm sóc bệnh nhân tâm thần',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'WardTypeID' => 7,
                'TypeName' => 'Khoa Ung Bướu',
                'Description' => 'Chăm sóc đặc biệt cho bệnh nhân ung thư',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'WardTypeID' => 8,
                'TypeName' => 'Khoa Tim Mạch',
                'Description' => 'Chăm sóc bệnh nhân mắc các bệnh lý về tim mạch',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'WardTypeID' => 9,
                'TypeName' => 'Khoa Hô Hấp',
                'Description' => 'Điều trị các bệnh lý về đường hô hấp',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'WardTypeID' => 10,
                'TypeName' => 'Khoa Tiêu Hóa',
                'Description' => 'Điều trị các bệnh lý về đường tiêu hóa',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
