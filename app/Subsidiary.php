<?php

namespace Gcr;

use Illuminate\Database\Eloquent\Model;

class Subsidiary extends Model
{
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
