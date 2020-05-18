<?php

namespace Gcr;

use Illuminate\Database\Eloquent\Model;

class Accounting extends Model
{
    protected $fillable = [
        'name',
        'email',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
