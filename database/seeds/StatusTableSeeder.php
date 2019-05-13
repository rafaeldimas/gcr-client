<?php

use Gcr\Status;
use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            'label' => 'Iniciado',
            'color' => '#fff176',
        ]);
        Status::create([
            'label' => 'Completo',
            'color' => '#4fc3f7',
        ]);
    }
}
