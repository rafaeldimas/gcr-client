<?php

use Gcr\Process;
use Gcr\Status;
use Gcr\User;
use Illuminate\Database\Seeder;

class ProcessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function ($user) {
            factory(Process::class, 5)->create([ 'user_id' => $user->id ]);
        });
    }
}
