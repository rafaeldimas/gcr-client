<?php

namespace Gcr;

use Carbon\Carbon;
use Gcr\Traits\AttributesSelectDynamically;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Owner extends Model
{
    use AttributesSelectDynamically;

    const JOB_ROLES_HOLDER = 1;
    const JOB_ROLES_ADMIN = 2;
    const JOB_ROLES_REPRESENTATIVE = 3;
    const JOB_ROLES_OTHER = 4;

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
        'job_roles' => [
            'Titular/Sócio',
            'Administrador',
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
        'job_roles',
        'job_roles_other',
        'marital_status',
        'wedding_regime',
        'rg',
        'rg_expedition',
        'date_of_birth',
        'cpf'
    ];

    protected $casts = [
        'job_roles' => 'array',
        'rg_expedition' => 'date:Y-m-d',
        'date_of_birth' => 'date:Y-m-d',
    ];

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    /**
     * @param int $jobRole
     * @return bool
     */
    public function containJobRole($jobRole)
    {
        return array_has($this->job_roles, $jobRole);
    }

    /**
     * @return bool
     */
    public function showJobRolesOther()
    {
        return $this->containJobRole(self::JOB_ROLES_OTHER);
    }

    /**
     * @return bool
     */
    public function showWeddingWegime()
    {
        return $this->marital_status === self::MARITAL_STATUS_MARRIED;
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
