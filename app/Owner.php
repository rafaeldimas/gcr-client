<?php

namespace Gcr;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = [ 'id', 'name', 'marital_status', 'rg', 'rg_expedition', 'cpf' ];

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function setRgExpeditionAttribute($value)
    {
        $this->attributes['rg_expedition'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->toDateString() : null;
    }

    public function getRgExpeditionAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y') : null;
    }
}
