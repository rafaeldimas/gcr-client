<?php

use Gcr\Address;
use Gcr\Company;
use Gcr\Owner;
use Gcr\Subsidiary;
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
        Owner::all()->each(function (Owner $owner) {
            $address = factory(Address::class)->create();

            $owner->address()->associate($address);
            $owner->save();
        });

        Company::all()->each(function (Company $company) {
            $address = factory(Address::class)->create();

            $company->address()->associate($address);
            $company->save();
        });

        Subsidiary::all()->each(function (Subsidiary $subsidiary) {
            $address = factory(Address::class)->create();

            $subsidiary->address()->associate($address);
            $subsidiary->save();
        });
    }
}
