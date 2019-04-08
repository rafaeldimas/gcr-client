<?php

namespace Gcr;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [ 'id', 'postcode', 'street', 'number', 'district', 'city', 'state', 'country' ];

    public function owner()
    {
        return $this->hasOne(Owner::class);
    }
}
