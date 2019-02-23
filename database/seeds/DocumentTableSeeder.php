<?php

use Gcr\Document;
use Gcr\Process;
use Illuminate\Database\Seeder;

class DocumentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Process::all()->each(function ($process) {
            factory(Document::class, 3)->create(['process_id' => $process->id]);
        });
    }
}
