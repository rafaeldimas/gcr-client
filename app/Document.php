<?php

namespace Gcr;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function process()
    {
        return $this->belongsTo(Process::class);
    }
}
