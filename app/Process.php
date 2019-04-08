<?php

namespace Gcr;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $fillable = [ 'status', 'protocol', 'type_company', 'type', 'description' ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (Process $model) {
            $model->user()->associate(auth()->user());
            $model->protocol = $model->type_company.'-'.$model->type.'-'.date('YmdHis');
        });
    }

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

    public function setTypeCompanyAttribute($value)
    {
        $this->attributes['type_company'] = strtoupper($value);
    }

    public function getStatusAttribute($value)
    {
        return $value ? 'Finalizado' : 'Pendente';
    }
}
