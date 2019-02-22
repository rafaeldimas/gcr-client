<?php

use Gcr\Owner;
use Gcr\Process;
use Illuminate\Database\Seeder;

class OwnerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Process::all()->each(function ($process) {
            factory(Owner::class, 2)->create(['process_id' => $process->id]);
        });
    }
}
