<?php

use Gcr\Process;
use Gcr\Viability;
use Illuminate\Database\Seeder;

class ViabilityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Process::all()->each(function ($process) {
            if ($process->showViability()) {
                $viability = factory(Viability::class, 1)->create();
                $process->viability()->associate($viability);
            }
        });
    }
}
