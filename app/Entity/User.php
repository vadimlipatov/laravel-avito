<?php

namespace App\Entity;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string email
 * @property string status
 */
class User extends Authenticatable
{
    use Notifiable;

    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    protected $fillable = [
        'name', 'email', 'password', 'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getStatuses() {
        return [
            self::STATUS_WAIT => 'Waiting',
            self::STATUS_ACTIVE => 'Active',
        ];
    }
}
