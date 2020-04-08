<?php

use Gcr\Status;
use Illuminate\Database\Seeder;

class StatusTableSeederProduction extends Seeder
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
            'text_white' => false,
        ]);
        Status::create([
            'label' => 'Completo',
            'color' => '#4fc3f7',
            'text_white' => true,
        ]);
    }
}
