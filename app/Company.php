<?php

namespace Gcr;

use Carbon\Carbon;
use Gcr\Traits\AttributesSelectDynamically;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use AttributesSelectDynamically;

    const SIZE_SMALL = 1;
    const SIZE_MEDIUM = 2;
    const SIZE_LARGE = 3;
    const SIZE_OTHER = 4;

    protected static $labels = [
        'size' => [
            'Pequeno Porte',
            'Médio Porte',
            'Grande Porte',
            'Demais',
        ]
    ];

    protected $fillable = [ 'id', 'name', 'share_capital', 'activity_description', 'size', 'signed' ];

    protected $casts = [
        'signed' => 'date:Y-m-d',
    ];

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

    public function cnaes()
    {
        return $this->hasMany(Cnae::class);
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
