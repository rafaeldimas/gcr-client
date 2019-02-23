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
        Owner::all()->each(function ($owner) {
            /** @var Address $address */
            $address = factory(Address::class)->create();
            /** @var Owner $owner */
            $owner->address()->associate($address);
            $owner->save();
        });

        Company::all()->each(function ($company) {
            /** @var Address $address */
            $address = factory(Address::class)->create();
            /** @var Company $company */
            $company->address()->associate($address);
            $company->save();
        });

        Subsidiary::all()->each(function ($subsidiary) {
            /** @var Address $address */
            $address = factory(Address::class)->create();
            /** @var Subsidiary $subsidiary */
            $subsidiary->address()->associate($address);
            $subsidiary->save();
        });
    }
}
