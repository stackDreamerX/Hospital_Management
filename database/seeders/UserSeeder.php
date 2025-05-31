<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Admin users
            [
                'RoleID' => 'admin',
                'username' => 'admin',
                'FullName' => 'Nguyễn Văn An',
                'Email' => 'admin@benhvien.vn',
                'password' => bcrypt('password'),
                'PhoneNumber' => '0912345678',
                'DateOfBirth' => '1985-05-15',
                'Gender' => 'Male',
                'Address' => '123 Lê Lợi, Quận 1, TP. Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Doctor users
            [
                'RoleID' => 'doctor',
                'username' => 'bshuong',
                'FullName' => 'Trần Thị Hương',
                'Email' => 'huong.tran@benhvien.vn',
                'password' => bcrypt('password'),
                'PhoneNumber' => '0923456789',
                'DateOfBirth' => '1980-03-12',
                'Gender' => 'Female',
                'Address' => '45 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'RoleID' => 'doctor',
                'username' => 'bsminh',
                'FullName' => 'Phạm Văn Minh',
                'Email' => 'minh.pham@benhvien.vn',
                'password' => bcrypt('password'),
                'PhoneNumber' => '0934567890',
                'DateOfBirth' => '1978-07-22',
                'Gender' => 'Male',
                'Address' => '78 Điện Biên Phủ, Quận 3, TP. Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'RoleID' => 'doctor',
                'username' => 'bsthao',
                'FullName' => 'Lê Thị Thảo',
                'Email' => 'thao.le@benhvien.vn',
                'password' => bcrypt('password'),
                'PhoneNumber' => '0945678901',
                'DateOfBirth' => '1982-11-05',
                'Gender' => 'Female',
                'Address' => '15 Võ Văn Tần, Quận 3, TP. Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Patient users
            [
                'RoleID' => 'patient',
                'username' => 'patient1',
                'FullName' => 'Nguyễn Thị Lan',
                'Email' => 'lan.nguyen@gmail.com',
                'password' => bcrypt('password'),
                'PhoneNumber' => '0956789012',
                'DateOfBirth' => '1990-02-18',
                'Gender' => 'Female',
                'Address' => '234 Lý Thường Kiệt, Quận 10, TP. Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'RoleID' => 'patient',
                'username' => 'patient2',
                'FullName' => 'Trần Văn Hùng',
                'Email' => 'hung.tran@gmail.com',
                'password' => bcrypt('password'),
                'PhoneNumber' => '0967890123',
                'DateOfBirth' => '1985-08-30',
                'Gender' => 'Male',
                'Address' => '56 Nguyễn Du, Quận 1, TP. Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'RoleID' => 'patient',
                'username' => 'patient3',
                'FullName' => 'Phạm Thị Hà',
                'Email' => 'ha.pham@gmail.com',
                'password' => bcrypt('password'),
                'PhoneNumber' => '0978901234',
                'DateOfBirth' => '1995-04-10',
                'Gender' => 'Female',
                'Address' => '78 Bùi Viện, Quận 1, TP. Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Converted staff to patient
            [
                'RoleID' => 'patient',
                'username' => 'nvthanh',
                'FullName' => 'Nguyễn Văn Thanh',
                'Email' => 'thanh.nguyen@benhvien.vn',
                'password' => bcrypt('password'),
                'PhoneNumber' => '0989012345',
                'DateOfBirth' => '1988-09-15',
                'Gender' => 'Male',
                'Address' => '90 Nguyễn Thị Minh Khai, Quận 3, TP. Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'RoleID' => 'patient',
                'username' => 'ttlinh',
                'FullName' => 'Trần Thị Linh',
                'Email' => 'linh.tran@benhvien.vn',
                'password' => bcrypt('password'),
                'PhoneNumber' => '0990123456',
                'DateOfBirth' => '1992-06-25',
                'Gender' => 'Female',
                'Address' => '25 Pasteur, Quận 1, TP. Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'RoleID' => 'doctor',
                'username' => 'ytmai',
                'FullName' => 'Lê Thị Mai',
                'Email' => 'mai.le@benhvien.vn',
                'password' => bcrypt('password'),
                'PhoneNumber' => '0901234567',
                'DateOfBirth' => '1989-12-12',
                'Gender' => 'Female',
                'Address' => '37 Hàm Nghi, Quận 1, TP. Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}