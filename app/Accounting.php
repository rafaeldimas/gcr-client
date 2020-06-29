<?php

namespace Gcr;

use Illuminate\Database\Eloquent\Model;

class Accounting extends Model
{
    protected $fillable = [
        'name',
        'email_1',
        'email_2',
        'email_3',
        'email_4',
        'email_5',
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
