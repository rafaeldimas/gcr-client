<?php

use Gcr\Address;
use Gcr\Owner;
use Illuminate\Database\Seeder;

class AddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Owner::all()->each(function ($owner) {
            /** @var Address $address */
            $address = factory(Address::class)->create();
            /** @var Owner $owner */
            $owner->address()->associate($address);
            $owner->save();
        });
    }
}
