<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicineSeeder extends Seeder
{
    public function run()
    {
        $medicines = [
            [
                'MedicineName' => 'Paracetamol 500mg',
                'ExpiryDate' => now()->addYears(2),
                'ManufacturingDate' => now()->subMonths(6),
                'UnitPrice' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'MedicineName' => 'Ampicillin 500mg',
                'ExpiryDate' => now()->addYears(2),
                'ManufacturingDate' => now()->subMonths(4),
                'UnitPrice' => 15000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'MedicineName' => 'Amoxicillin 500mg',
                'ExpiryDate' => now()->addYears(2),
                'ManufacturingDate' => now()->subMonths(5),
                'UnitPrice' => 12000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'MedicineName' => 'Vitamin C 1000mg',
                'ExpiryDate' => now()->addYears(3),
                'ManufacturingDate' => now()->subMonths(2),
                'UnitPrice' => 8000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'MedicineName' => 'Berberin',
                'ExpiryDate' => now()->addYears(2),
                'ManufacturingDate' => now()->subMonths(3),
                'UnitPrice' => 7000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'MedicineName' => 'Thuốc ho Bảo Thanh',
                'ExpiryDate' => now()->addYears(2),
                'ManufacturingDate' => now()->subMonths(4),
                'UnitPrice' => 45000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'MedicineName' => 'Dầu Gió Trường Sơn',
                'ExpiryDate' => now()->addYears(3),
                'ManufacturingDate' => now()->subMonths(6),
                'UnitPrice' => 25000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'MedicineName' => 'Thuốc đau dạ dày Dạ Hương',
                'ExpiryDate' => now()->addYears(2),
                'ManufacturingDate' => now()->subMonths(3),
                'UnitPrice' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'MedicineName' => 'Hoạt Huyết Dưỡng Não',
                'ExpiryDate' => now()->addYears(2),
                'ManufacturingDate' => now()->subMonths(4),
                'UnitPrice' => 65000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'MedicineName' => 'Eugica',
                'ExpiryDate' => now()->addYears(2),
                'ManufacturingDate' => now()->subMonths(5),
                'UnitPrice' => 32000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'MedicineName' => 'Panactol',
                'ExpiryDate' => now()->addYears(2),
                'ManufacturingDate' => now()->subMonths(6),
                'UnitPrice' => 9000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'MedicineName' => 'Tetracyclin 500mg',
                'ExpiryDate' => now()->addYears(2),
                'ManufacturingDate' => now()->subMonths(4),
                'UnitPrice' => 18000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'MedicineName' => 'Oresol',
                'ExpiryDate' => now()->addYears(3),
                'ManufacturingDate' => now()->subMonths(2),
                'UnitPrice' => 6000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'MedicineName' => 'Thuốc bổ máu Ferous',
                'ExpiryDate' => now()->addYears(2),
                'ManufacturingDate' => now()->subMonths(3),
                'UnitPrice' => 120000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'MedicineName' => 'Vitamin tổng hợp Centrum',
                'ExpiryDate' => now()->addYears(3),
                'ManufacturingDate' => now()->subMonths(2),
                'UnitPrice' => 190000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('medicines')->insert($medicines);
    }
}
