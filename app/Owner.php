<?php

namespace Gcr;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
