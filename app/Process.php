<?php

namespace Gcr;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $fillable = [ 'protocol' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function owner()
    {
        return $this->hasOne(Owner::class);
    }
}
