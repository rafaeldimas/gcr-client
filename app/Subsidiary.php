<?php

namespace Gcr;

use Gcr\Traits\AttributesSelectDynamically;
use Illuminate\Database\Eloquent\Model;

class Subsidiary extends Model
{
    use AttributesSelectDynamically;

    const REQUEST_OPENING = 1;
    const REQUEST_CANCELING = 2;
    const REQUEST_CHANGING_ACTIVITY = 3;
    const REQUEST_CHANGING_ADDRESS = 4;
    const REQUEST_CHANGING_CAPITAL = 5;

    protected static $labels = [
        'request' => [
            'Abertura',
            'Cancelamento',
            'Alteração Atividade',
            'Alteração Endereço',
            'Alteração Capital',
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
