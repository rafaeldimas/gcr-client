<?php

namespace Gcr;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [ 'label', 'color'];

    public function processes()
    {
        return $this->hasMany(Process::class);
    }

    public static function getStatusStarting()
    {
        return self::find(1);
    }

    public static function getStatusCompleted()
    {
        return self::find(2);
    }
}
