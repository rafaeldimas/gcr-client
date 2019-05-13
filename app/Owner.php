<?php

namespace Gcr;

use Carbon\Carbon;
use Gcr\Traits\AttributesSelectDynamically;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use AttributesSelectDynamically;

    const JOB_ROLE_HOLDER = 1;
    const JOB_ROLE_ADMIN = 2;
    const JOB_ROLE_DEPENDENT = 3;
    const JOB_ROLE_REPRESENTATIVE = 4;
    const JOB_ROLE_OTHER = 5;

    const MARITAL_STATUS_NOT_MARRIED = 1;
    const MARITAL_STATUS_MARRIED = 2;
    const MARITAL_STATUS_DIVORCED = 3;
    const MARITAL_STATUS_WIDOWER = 4;
    const MARITAL_STATUS_SEPARATE = 5;

    const WEDDING_REGIME_PARTIAL = 1;
    const WEDDING_REGIME_UNIVERSAL = 2;
    const WEDDING_REGIME_TOTAL = 3;
    const WEDDING_REGIME_REQUIRED = 4;
    const WEDDING_REGIME_OTHER = 5;

    protected static $labels = [
        'job_role' => [
            'Titular',
            'Administrador',
            'Dependente',
            'Representante',
            'Outro',
        ],
        'wedding_regime' => [
            'Comunhão parcial de bens',
            'Comunhão universal de bens',
            'Separação total',
            'Separação obrigatória',
            'Outro',
        ],
        'marital_status' => [
            'Solteiro',
            'Casado',
            'Divorciado',
            'Viúvo',
            'Separado',
        ],
    ];

    protected $fillable = [
        'id',
        'name',
        'type',
        'job_role',
        'job_role_other',
        'marital_status',
        'wedding_regime',
        'rg',
        'rg_expedition',
        'date_of_birth',
        'cpf'
    ];

    protected $casts = [
        'rg_expedition' => 'date:Y-m-d',
        'date_of_birth' => 'date:Y-m-d',
    ];

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
