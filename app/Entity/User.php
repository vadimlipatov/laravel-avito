<?php

namespace App\Entity;

use Carbon\Carbon;
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
 * @property string $phone
 * @property bool   $phone_auth
 * @property bool   $phone_verified
 * @property string $phone_verify_token
 * @property Carbon $phone_verify_token_expire
 * @property string $status
 *
 * @method static rolesList()
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

    protected $casts = [
        'phone_verified' => 'boolean',
        'phone_verify_token_expire' => 'datetime',
    ];

    public static function rolesList()
    {
        return [
            self::ROLE_USER => 'User',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_MODERATOR => 'Moderator',
        ];
    }

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

    public function isModerator()
    {
        return $this->role === self::ROLE_MODERATOR;
    }

    public function changeRole($role)
    {
        if (!array_key_exists($role, self::rolesList())) {
            throw new \InvalidArgumentException("Undefined role '{$role}'");
        }

        if ($this->role === $role) {
            throw new \DomainException("Role is already assigned.");
        }

        $this->update(['role' => $role]);
    }

    public function isPhoneVerified()
    {
        return $this->phone_verified;
    }

    public function unverifyPhone()
    {
        $this->phone_verified = false;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    public function requestPhoneVerification(Carbon $time)
    {
        if (empty($this->phone)) {
            throw new \DomainException("Phone number is empty.");
        }

        if (!empty($this->phone_verify_token) && $this->phone_verify_token_expire && $this->phone_verify_token_expire->gt($time)) {
            throw new \DomainException("Token is already requested.");
        }

        $this->phone_verified = false;
        $this->phone_verify_token = (string)random_int(10000, 99999);
        $this->phone_verify_token_expire = $time->copy()->addSeconds(300);
        $this->saveOrFail();

        return $this->phone_verify_token;
    }

    public function verifyPhone($token, Carbon $now): void
    {
        if ($token !== $this->phone_verify_token) {
            throw new \DomainException('Incorrect verify token.');
        }
        if ($this->phone_verify_token_expire->lt($now)) {
            throw new \DomainException('Token is expired.');
        }
        $this->phone_verified = true;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    public function enablePhoneAuth(): void
    {
        $this->phone_auth = true;
        $this->saveOrFail();
    }

    public function disablePhoneAuth(): void
    {
        $this->phone_auth = false;
        $this->saveOrFail();
    }

    public function isPhoneAuthEnabled(): bool
    {
        return $this->phone_auth;
    }

    public function hasFilledProfile()
    {
        return empty($this->name) || empty($this->last_name) || empty($this->isPhoneVerified());
    }
}
