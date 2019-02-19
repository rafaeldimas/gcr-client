<?php

use Gcr\Process;
use Gcr\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 10)->create()->each(function ($user) {
            factory(Process::class, 5)->create([
                'user_id' => $user->id
            ]);
        });
    }
}
