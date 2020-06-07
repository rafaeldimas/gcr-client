<?php

namespace Gcr;

use Gcr\Traits\AttributesSelectDynamically;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use AttributesSelectDynamically;

    const SIZE_SMALL = 1;
    const SIZE_MEDIUM = 2;
    const SIZE_OTHER = 3;

    protected static $labels = [
        'size' => [
            'EPP',
            'ME',
            'Demais',
        ]
    ];

    protected $fillable = [
        'id',
        'transformation_with_change',
        'name',
        'nire',
        'cnpj',
        'activity_start',
        'share_capital',
        'activity_description',
        'size',
        'signed',
    ];

    protected $casts = [
        'transformation_with_change' => 'array',
        'signed' => 'date:Y-m-d',
        'activity_start' => 'date:Y-m-d',
    ];

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function subsidiaries()
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

    public function sizeCode()
    {
        return array_get(self::attributeOptions('size'), (string) $this->size, '');
    }

    public function cnaesStringFormated()
    {
        return $this->cnaes->implode('number', ', ');
    }
}
