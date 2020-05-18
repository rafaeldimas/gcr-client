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

    const AVCB_CLCB_NUMBER_TYPE_AVCB = 1;
    const AVCB_CLCB_NUMBER_TYPE_CLCB = 2;

    protected $fillable = [
        'property_type',
        'registration_number',
        'property_area',
        'establishment_area',
        'avcb_clcb_number',
        'avcb_clcb_number_type',
        'establishment_has_avcb_clcb',
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
        'avcb_clcb_number_type' => [
            'AVCB',
            'CLCB',
        ],
    ];

    public function process()
    {
        return $this->hasOne(Process::class);
    }

    public function getEstablishmentHasAvcbClcbHumanAttribute()
    {
        return $this->establishment_has_avcb_clcb ? 'Sim' : 'Não';
    }

    public function getSameAsBusinessAddressHumanAttribute()
    {
        return $this->same_as_business_address ? 'Sim' : 'Não';
    }

    public function getThirstHumanAttribute()
    {
        return $this->thirst ? 'Sim' : 'Não';
    }

    public function getAdministrativeOfficeHumanAttribute()
    {
        return $this->administrative_office ? 'Sim' : 'Não';
    }

    public function getClosedDepositHumanAttribute()
    {
        return $this->closed_deposit ? 'Sim' : 'Não';
    }

    public function getWarehouseHumanAttribute()
    {
        return $this->warehouse ? 'Sim' : 'Não';
    }

    public function getRepairWorkshopHumanAttribute()
    {
        return $this->repair_workshop ? 'Sim' : 'Não';
    }

    public function getGarageHumanAttribute()
    {
        return $this->garage ? 'Sim' : 'Não';
    }

    public function getFuelSupplyUnitHumanAttribute()
    {
        return $this->fuel_supply_unit ? 'Sim' : 'Não';
    }

    public function getExposurePointHumanAttribute()
    {
        return $this->exposure_point ? 'Sim' : 'Não';
    }

    public function getTrainingCenterHumanAttribute()
    {
        return $this->training_center ? 'Sim' : 'Não';
    }

    public function getDataProcessingCenterHumanAttribute()
    {
        return $this->data_processing_center ? 'Sim' : 'Não';
    }

    public function propertyTypeCode()
    {
        return array_get(self::attributeOptions('property_type'), (string) $this->property_type, '');
    }
}
