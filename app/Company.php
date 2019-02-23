<?php

namespace Gcr;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function subsidiary()
    {
        return $this->hasOne(Subsidiary::class);
    }
}
