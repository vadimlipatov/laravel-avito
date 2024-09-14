<?php

namespace App\Entity;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $verify_token
 * @property string $status
 * @property string $role
 * @property string $phone
 * @property bool $phone_verified
 * @property string $phone_verify_token
 */
class User extends Authenticatable
{
    use Notifiable;

    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MODERATOR = 'moderator';

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'phone_verified',
        'phone_verify_token',
        'password',
        'status',
        'role',
        'verify_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function register($name, $email, $password)
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'verify_token' => Str::uuid(),
            'role' => self::ROLE_USER,
            'status' => self::STATUS_WAIT,
        ]);
    }

    public static function new($name, $email)
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt(Str::random()),
            'role' => self::ROLE_USER,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function verify()
    {
        if (!$this->isWait()) {
            throw new \DomainException("User is already verified.");
        }

        $this->update([
            'status' => self::STATUS_ACTIVE,
            'verify_token' => null
        ]);
    }

    public function isWait()
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function changeRole($role)
    {
        if (!\in_array($role, [self::ROLE_ADMIN, self::ROLE_USER], true)) {
            throw new \InvalidArgumentException("Undefined role '{$role}'");
        }

        if ($this->role === $role) {
            throw new \DomainException("Role is already assigned.");
        }

        $this->update(['role' => $role]);
    }
}
