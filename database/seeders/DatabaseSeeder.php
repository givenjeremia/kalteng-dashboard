<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(SuperAdminSeeder::class);
        // $this->call(RoleSeeder::class);
        // $this->call(BudgetSeeder::class);


        $this->call(NewBudgetSeeder::class);
        $this->call(IKPASeeder::class);
        $this->call(EPerformanceSeeder::class);
        $this->call(EMonevSeeder::class);

        


    }
}
