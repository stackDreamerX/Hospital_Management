<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            [
                'ProviderName' => 'Công ty Cổ phần Dược Hậu Giang (DHG)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ProviderName' => 'Công ty Cổ phần Dược phẩm Imexpharm',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ProviderName' => 'Công ty Cổ phần Traphaco',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ProviderName' => 'Công ty Cổ phần Dược OPC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ProviderName' => 'Công ty Cổ phần Pymepharco',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ProviderName' => 'Công ty Cổ phần Dược phẩm Hà Tây',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ProviderName' => 'Công ty Cổ phần Dược phẩm Domesco',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ProviderName' => 'Công ty Cổ phần Dược phẩm TV.Pharm',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ProviderName' => 'Công ty Cổ phần Dược phẩm Bidiphar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ProviderName' => 'Công ty TNHH Sanofi-Aventis Việt Nam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ProviderName' => 'Công ty TNHH Novartis Việt Nam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ProviderName' => 'Công ty TNHH Pfizer Việt Nam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('providers')->insert($providers);
    }
}