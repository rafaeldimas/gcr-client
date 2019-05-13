<?php

use Gcr\Cnae;
use Gcr\Company;
use Illuminate\Database\Seeder;

class CnaeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::all()->each(function ($company) {
            factory(Cnae::class, mt_rand(1, 5))->create(['company_id' => $company->id]);
        });
    }
}
