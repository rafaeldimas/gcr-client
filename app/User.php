<?php

namespace Gcr;

use Gcr\Traits\AccessLinksController;
use Gcr\Traits\AttributesSelectDynamically;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable, AttributesSelectDynamically, AccessLinksController;

    const TYPE_ADMIN = 1;
    const TYPE_CUSTOMER = 2;

    protected static $labels = [
        'type' => [
            'Administrador',
            'Cliente',
        ]
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type', 'logo', 'phone', 'mobile_phone', 'password_change_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_change_at' => 'datetime',
    ];

    public function processes()
    {
        return $this->hasMany(Process::class);
    }

    public function isAdmin()
    {
        return $this->type === self::TYPE_ADMIN;
    }

    public function logoUrl()
    {
        return Storage::url($this->logo);
    }

    public function getTypeLabelAttribute()
    {
        return array_get(User::attributeOptions('type'), $this->type);
    }
}
