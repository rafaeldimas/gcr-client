<?php

use Gcr\User;
use Illuminate\Database\Seeder;

class UsersTableSeederProduction extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'name' => 'Rafael Dimas',
            'email' => 'rafael_citotame@hotmail.com',
            'type' => User::TYPE_ADMIN,
        ]);

        factory(User::class)->create([
            'name' => 'Gustavo Camargo',
            'email' => 'gustavocamargo@gcrlegalizacao.com.br',
            'type' => User::TYPE_ADMIN,
        ]);

        factory(User::class)->create([
            'name' => 'Rafael Marçal',
            'email' => 'rafael@gcrlegalizacao.com.br',
            'type' => User::TYPE_ADMIN,
        ]);
    }
}
