<?php

namespace Gcr;

use Gcr\Traits\AccessLinksController;
use Gcr\Traits\AttributesSelectDynamically;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Process|Builder currentUser()
 */
class Process extends Model
{
    use AttributesSelectDynamically, AccessLinksController;

    const OPERATION_CREATING = 1;
    const OPERATION_UPDATING = 2;
    const OPERATION_DELETING = 3;
    const OPERATION_TRANSFORMATION = 4;

    const TYPE_COMPANY_BUSINESSMAN = 1;
    const TYPE_COMPANY_SOCIETY = 2;
    const TYPE_COMPANY_EIRELI = 3;
    const TYPE_COMPANY_OTHER = 4;

    const FIELDS_EDITING_OWNERS = 'owners';
    const FIELDS_EDITING_CAPITAL = 'capital';
    const FIELDS_EDITING_COMPANY_NAME = 'company name';
    const FIELDS_EDITING_COMPANY_SIZE = 'company size';
    const FIELDS_EDITING_COMPANY_CNAES = 'company_cnaes';
    const FIELDS_EDITING_COMPANY_ADDRESS = 'company_address';
    const FIELDS_EDITING_TRANSFER_TO_ANOTHER_UF = 'transfer_to_another_uf';
    const FIELDS_EDITING_TRANSFER_FROM_ANOTHER_UF_TO_SP = 'transfer_from_another_uf_to_sp';
    const FIELDS_EDITING_COMPANY = 'company';
    const FIELDS_EDITING_SUBSIDIARY = 'subsidiary';

    protected static $labels = [
        'operation' => [
            'Constituição',
            'Alteração',
            'Baixa',
            'Transformação',
        ],
        'type_company' => [
            'Empresário individual',
            'Sociedade Limitada',
            'Eireli',
            'Outros',
        ],
        'fields_editing' => [
            'Pessoas (Empresario, Integrantes, Sócios)',
            'Alteração de Capital',
            'Alteração de Razão Social',
            'Alteração de Porte da Empresa',
            'Cnaes da Empresa',
            'Endereço da Empresa',
            'Transferência de Sede Para Outra UF',
            'Transferência de Sede de Outra UF para SP',
            'Outras Cláusulas Contratuais',
            'Filiais',
        ]
    ];

    protected $fillable = [
        'editing',
        'protocol',
        'type_company',
        'new_type_company',
        'operation',
        'description_of_changes',
        'description',
        'fields_editing',
    ];

    protected $casts = [
        'fields_editing' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (Process $model) {
            if (!$model->user_id) {
                if (auth()->user()) {
                    $model->user()->associate(auth()->user());
                }
            }
            $model->protocol = date('YmdHis').mt_rand();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function viability()
    {
        return $this->belongsTo(Viability::class);
    }

    public function statuses()
    {
        return $this->belongsToMany(Status::class)->withTimestamps()->withPivot('description');
    }

    public function statusLatest()
    {
        return $this->statuses()->latest('pivot_created_at');
    }

    public function getStatusLatestFirstAttribute()
    {
        return $this->statusLatest->first();
    }

    public function owners()
    {
        return $this->hasMany(Owner::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function scopeCurrentUser($query)
    {
        $user = auth()->user();
        if ($user && !$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }
        return $query;
    }

    public function getEditingHumanAttribute()
    {
        return $this->editing ? 'Sim' : 'Não';
    }

    public function getScannedHumanAttribute()
    {
        return $this->scanned ? 'Sim' : 'Não';
    }

    public function getPostOfficeHumanAttribute()
    {
        return $this->post_office ? 'Sim' : 'Não';
    }

    public function isEditing()
    {
        return (bool) $this->editing;
    }

    public function typeCompanyCode()
    {
        $codes = array_combine(self::attributeCodes('type_company'), [
            'EMPRESARIO-INDIVIDUAL',
            'SOCIEDADE-LIMITADA',
            'EIRELI',
            'OUTROS',
        ]);
        return array_get($codes, $this->type_company, '');
    }

    public function newTypeCompanyCode()
    {
        $codes = array_combine(self::attributeCodes('type_company'), [
            'EMPRESARIO-INDIVIDUAL',
            'SOCIEDADE-LIMITADA',
            'EIRELI',
            'OUTROS',
        ]);
        return array_get($codes, $this->new_type_company, '');
    }

    public function operationCode()
    {
        $codes = array_combine(self::attributeCodes('operation'), [
            'CONSTITUICAO',
            'ALTERACAO',
            'BAIXA',
            'TRANSFORMACAO',
        ]);
        return array_get($codes, $this->operation, '');
    }

    public function fieldsEditingCode()
    {
        $result = [];
        if (is_array($this->fields_editing)) {
            $labels = self::attributeOptions('fields_editing');
            foreach ($this->fields_editing as $field) {
                $result[$field] = array_get($labels, $field, '');
            }
        }
        return $result;
    }

    public function isBusinessman()
    {
        return $this->type_company === self::TYPE_COMPANY_BUSINESSMAN;
    }

    public function isSociety()
    {
        return $this->type_company === self::TYPE_COMPANY_SOCIETY;
    }

    public function isEireli()
    {
        return $this->type_company === self::TYPE_COMPANY_EIRELI;
    }

    public function isOther()
    {
        return $this->type_company === self::TYPE_COMPANY_OTHER;
    }

    public function isTransformationBusinessman()
    {
        return $this->isTransformation() && $this->new_type_company === self::TYPE_COMPANY_BUSINESSMAN;
    }

    public function isTransformationSociety()
    {
        return $this->isTransformation() && $this->new_type_company === self::TYPE_COMPANY_SOCIETY;
    }

    public function isTransformationEireli()
    {
        return $this->isTransformation() && $this->new_type_company === self::TYPE_COMPANY_EIRELI;
    }

    public function isTransformationOther()
    {
        return $this->isTransformation() && $this->new_type_company === self::TYPE_COMPANY_OTHER;
    }

    public function isCreating()
    {
        return $this->operation === self::OPERATION_CREATING;
    }

    public function isUpdating()
    {
        return $this->operation === self::OPERATION_UPDATING;
    }

    public function isDeleting()
    {
        return $this->operation === self::OPERATION_DELETING;
    }

    public function isTransformation()
    {
        return $this->operation === self::OPERATION_TRANSFORMATION;
    }

    public function isEditingOwners()
    {
        if (is_array($this->fields_editing)) {
            return in_array(self::FIELDS_EDITING_OWNERS, $this->fields_editing);
        }

        return false;
    }

    public function isEditingCapital()
    {
        if (is_array($this->fields_editing)) {
            return in_array(self::FIELDS_EDITING_CAPITAL, $this->fields_editing);
        }

        return false;
    }

    public function isEditingCompanyName()
    {
        if (is_array($this->fields_editing)) {
            return in_array(self::FIELDS_EDITING_COMPANY_NAME, $this->fields_editing);
        }

        return false;
    }

    public function isEditingCompanySize()
    {
        if (is_array($this->fields_editing)) {
            return in_array(self::FIELDS_EDITING_COMPANY_SIZE, $this->fields_editing);
        }

        return false;
    }

    public function isEditingCompanyCnaes()
    {
        if (is_array($this->fields_editing)) {
            return in_array(self::FIELDS_EDITING_COMPANY_CNAES, $this->fields_editing);
        }

        return false;
    }

    public function isEditingCompanyAddress()
    {
        if (is_array($this->fields_editing)) {
            return in_array(self::FIELDS_EDITING_COMPANY_ADDRESS, $this->fields_editing);
        }

        return false;
    }

    public function isEditingTransferToAnotherUf()
    {
        if (is_array($this->fields_editing)) {
            return in_array(self::FIELDS_EDITING_TRANSFER_TO_ANOTHER_UF, $this->fields_editing);
        }

        return false;
    }

    public function isEditingTransferFromAnotherUfToSp()
    {
        if (is_array($this->fields_editing)) {
            return in_array(self::FIELDS_EDITING_TRANSFER_FROM_ANOTHER_UF_TO_SP, $this->fields_editing);
        }

        return false;
    }

    public function isEditingCompany()
    {
        if (is_array($this->fields_editing)) {
            return in_array(self::FIELDS_EDITING_COMPANY, $this->fields_editing);
        }

        return false;
    }

    public function isEditingSubsidiary()
    {
        if (is_array($this->fields_editing)) {
            return in_array(self::FIELDS_EDITING_SUBSIDIARY, $this->fields_editing);
        }

        return false;
    }

    public function showViability()
    {
        if ($this->isCreating() || $this->isTransformation()) {
            return true;
        }

        if ($this->isUpdating()) {
            return $this->isEditingCompany()
                || $this->isEditingCompanyName()
                || $this->isEditingCompanyCnaes()
                || $this->isEditingCompanyAddress();
        }

        return false;
    }

    /**
     * @param Model|null $model
     * @return string
     */
    public function linkShow(Model $model = null)
    {
        return route("{$this->getBaseRouteModel($model)}.show", $model ?: $this);
    }
}
