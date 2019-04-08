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
        if ($value && Carbon::hasFormat($value, 'd/m/Y')) {
            $value = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
        }
        $this->attributes['rg_expedition'] = $value;
    }

    public function getRgExpeditionAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y') : null;
    }
}
