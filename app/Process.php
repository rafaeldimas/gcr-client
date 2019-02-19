<?php

namespace Gcr;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $fillable = ['protocol'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
