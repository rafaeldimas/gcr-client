<?php

namespace Gcr;

use Illuminate\Database\Eloquent\Model;

class Cnae extends Model
{
    protected $fillable = [ 'number' ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
