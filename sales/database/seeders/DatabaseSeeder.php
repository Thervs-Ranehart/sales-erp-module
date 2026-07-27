<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EmployeeSeeder::class,
            SalesForecastingSeeder::class,
            AfterSalesSupportSeeder::class,
            WarrantyClaimSeeder::class,
            ServiceRequestSeeder::class,
            ResolutionTrackingSeeder::class,
            SatisfactionMonitoringSeeder::class,
        ]);
    }
}
