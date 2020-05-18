<?php

namespace Gcr;

use Gcr\Traits\AccessLinksController;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use AccessLinksController;

    protected $fillable = [ 'label', 'color', 'text_white' ];

    public function processes()
    {
        return $this->belongsToMany(Process::class)->withTimestamps()->withPivot('description');
    }

    public function getTextWhiteHumanAttribute()
    {
        return $this->text_white ? 'Sim' : 'Não';
    }

    public static function getStatusStarting()
    {
        return self::find(1);
    }

    public static function getStatusCompleted()
    {
        return self::find(2);
    }

    /**
     * @param Process $process
     * @return bool
     */
    public function isLastStatusByProcess(Process $process)
    {
        return $this->id === $process->statusLatestFirst->id;
    }
}
