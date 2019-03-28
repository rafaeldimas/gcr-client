<?php

namespace Gcr;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $fillable = [ 'protocol' ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->protocol = $model->type.'-'.date('YmdHis');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function owner()
    {
        return $this->hasMany(Owner::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function document()
    {
        return $this->hasMany(Document::class);
    }

    public function scopeCurrentUser($query)
    {
        if (auth()->user()->type !== User::USER_ADMIN) {
            $query->where('user_id', auth()->user()->id);
        }
        return $query;
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = strtoupper($value);
    }

    public function getStatusAttribute($value)
    {
        return $value ? 'Finalizado' : 'Pendente';
    }
}