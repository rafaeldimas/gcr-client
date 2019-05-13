<?php

namespace Gcr;

use Gcr\Traits\AttributesSelectDynamically;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use AttributesSelectDynamically;

    const TYPE_RG = 1;
    const TYPE_CPF = 2;
    const TYPE_IPTU = 3;
    const TYPE_OTHER = 4;

    protected static $labels = [
        'type' => [
            'RG',
            'CPF',
            'IPTU',
            'Outros',
        ],
    ];

    protected $fillable = [ 'type', 'file' ];

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function getRouteFile()
    {
        return route('documents', [ $this->file ]);
    }

    public function isRg()
    {
        return $this->type === self::TYPE_RG;
    }

    public function isCpf()
    {
        return $this->type === self::TYPE_CPF;
    }

    public function isIptu()
    {
        return $this->type === self::TYPE_IPTU;
    }

    public function isOther()
    {
        return $this->type === self::TYPE_OTHER;
    }

    public function showIptu()
    {
        if (!$this->isIptu()) {
            return true;
        }
        return $this->process->showViability();
    }
}
