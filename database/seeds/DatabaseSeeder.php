<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
             UsersTableSeeder::class,
             StatusTableSeeder::class,
             ProcessTableSeeder::class,
             ViabilityTableSeeder::class,
             OwnerTableSeeder::class,
             CompanyTableSeeder::class,
             SubsidiaryTableSeeder::class,
             CnaeTableSeeder::class,
             AddressTableSeeder::class,
             DocumentTableSeeder::class,
         ]);
    }
}
