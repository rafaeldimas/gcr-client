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
            $ownerNumber = $process->isBusinessman()
                ? 1
                : mt_rand($process->isSociety() ? 2 : 1, 5);
            factory(Owner::class, $ownerNumber)->create(['process_id' => $process->id]);
        });
    }
}
