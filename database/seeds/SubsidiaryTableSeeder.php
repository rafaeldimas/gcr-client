<?php

use Gcr\Company;
use Gcr\Subsidiary;
use Illuminate\Database\Seeder;

class SubsidiaryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::all()->each(function ($company) {
            if (rand(0, 10) >= 5) factory(Subsidiary::class)->create(['company_id' => $company->id]);
        });
    }
}
