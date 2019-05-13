<?php

namespace Gcr;

use Gcr\Traits\AttributesSelectDynamically;
use Illuminate\Database\Eloquent\Model;

class Viability extends Model
{
    use AttributesSelectDynamically;

    const PROPERTY_TYPE_URBAN = 1;
    const PROPERTY_TYPE_RURAL = 2;
    const PROPERTY_TYPE_WITHOUT_REGULARIZATION = 3;

    protected $fillable = [
        'property_type',
        'registration_number',
        'property_area',
        'establishment_area',
        'same_as_business_address',
        'thirst',
        'administrative_office',
        'closed_deposit',
        'warehouse',
        'repair_workshop',
        'garage',
        'fuel_supply_unit',
        'exposure_point',
        'training_center',
        'data_processing_center',
    ];

    protected static $labels = [
        'property_type' => [
            'Urbano',
            'Rural',
            'Sem regularização',
        ],
    ];

    public function process()
    {
        return $this->hasOne(Process::class);
    }
}
