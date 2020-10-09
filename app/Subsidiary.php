<?php

namespace Gcr;

use Gcr\Traits\AttributesSelectDynamically;
use Illuminate\Database\Eloquent\Model;

class Subsidiary extends Model
{
    use AttributesSelectDynamically;

    const REQUEST_OPENING = 1;
    const REQUEST_CANCELING = 2;
    const REQUEST_CHANGING = 3;

    const FIELDS_CHANGED_ACTIVITY = 1;
    const FIELDS_CHANGED_ADDRESS = 2;
    const FIELDS_CHANGED_CAPITAL = 3;

    protected static $labels = [
        'request' => [
            'Abertura',
            'Cancelamento',
            'Alteração',
        ],
        'fields_changed' => [
            'Alteração Atividade',
            'Alteração Endereço',
            'Alteração Capital',
        ]
    ];

    protected $fillable = [
        'request',
        'fields_changed',
        'nire',
        'cnpj',
        'share_capital',
        'activity_description',
    ];

    protected $casts = [
        'fields_changed' => 'array',
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

    public function requestCode()
    {
        return array_get(self::attributeOptions('request'), (string) $this->request, '');
    }

    public function cnaesStringFormated()
    {
        return $this->cnaes->implode('number', ', ');
    }

    public function fieldsChangedStringFormated()
    {
        $result = [];
        if (is_array($this->fields_changed)) {
            $labels = self::attributeOptions('fields_changed');
            foreach ($this->fields_changed as $field) {
                $result[$field] = array_get($labels, $field, '');
            }
        }
        return implode(', ', $result);
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
