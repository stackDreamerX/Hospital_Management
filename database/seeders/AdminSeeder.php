<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra xem đã có admin với email này chưa
        $admin = DB::table('tbl_admin')->where('admin_email', 'admin@gmail.com')->first();

        if (!$admin) {
            DB::table('tbl_admin')->insert([
                'admin_email' => 'admin@gmail.com',
                'admin_password' => bcrypt('123456'),
                'admin_name' => 'Administrator',
                'admin_phone' => '0123456789',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}