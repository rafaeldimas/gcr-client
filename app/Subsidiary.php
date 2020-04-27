<?php

namespace Gcr;

use Gcr\Traits\AttributesSelectDynamically;
use Illuminate\Database\Eloquent\Model;

class Subsidiary extends Model
{
    use AttributesSelectDynamically;

    const REQUEST_OPENING = 1;
    const REQUEST_CHANGING = 2;
    const REQUEST_CANCELING = 3;

    protected static $labels = [
        'request' => [
            'Abertura',
            'Alteração',
            'Cancelamento',
        ]
    ];

    protected $fillable = [
        'request',
        'nire',
        'cnpj',
        'share_capital',
        'activity_description',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
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
