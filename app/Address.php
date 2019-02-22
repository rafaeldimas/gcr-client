<?php

namespace Gcr;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public function owner()
    {
        return $this->hasOne(Owner::class);
    }
}
