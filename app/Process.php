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

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function document()
    {
        return $this->hasMany(Document::class);
    }
}
