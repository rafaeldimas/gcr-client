<?php

use Gcr\Company;
use Gcr\Process;
use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Process::all()->each(function ($process) {
            factory(Company::class)->create(['process_id' => $process->id]);
        });
    }
}
