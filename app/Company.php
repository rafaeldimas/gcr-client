<?php

namespace Gcr;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [ 'id', 'name', 'share_capital', 'activity_description', 'size', 'signed' ];

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function subsidiary()
    {
        return $this->hasMany(Subsidiary::class);
    }

    public function setSignedAttribute($value)
    {
        if ($value && Carbon::hasFormat($value, 'd/m/Y')) {
            $value = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
        }
        $this->attributes['signed'] = $value;
    }

    public function getSignedAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y') : null;
    }

    public function setShareCapitalAttribute($value)
    {
        $this->attributes['share_capital'] = ($value && is_string($value)) ? str_replace(['.', ','], ['', '.'], $value) : null;
    }

    public function getShareCapitalAttribute($value)
    {
        return $value ? number_format($value, 2, ',', '.') : null;
    }
}
