<?php

namespace App\Entity\Adverts\Advert;

use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Entity\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $category_id
 * @property int    $region_id
 * @property int    $price
 * @property string $title
 * @property string $content
 * @property string $address
 * @property string $status
 * @property string $reject_reason
 * @property string $created_at
 * @property string $updated_at
 * @property string $published_at
 * @property string $expires_at
 *
 * @property Category $category
 * @property Value[] $values
 *
 * @method static Builder forUser(User $user)
 */
class Advert extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_MODERATION = 'moderation';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CLOSED = 'closed';

    protected $table = 'advert_adverts';

    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function statusList()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_MODERATION => 'Moderation',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_CLOSED => 'Closed',
        ];
    }

    public function isDraft()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isOnModeration()
    {
        return $this->status === self::STATUS_MODERATION;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function values()
    {
        return $this->hasMany(Value::class, 'advert_id', 'id');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class, 'advert_id', 'id');
    }

    public function sendToModeration()
    {
        if (!$this->isDraft()) {
            throw new \DomainException('Advert is not a draft.');
        }

        if (!$this->photos()->count()) {
            throw new \DomainException('Upload photos.');
        }

        $this->update(['status' => self::STATUS_MODERATION]);
    }

    public function moderate(Carbon $date)
    {
        if ($this->isOnModeration()) {
            throw new \DomainException('Advert is not sent to moderation.');
        }

        $this->update([
            'status' => self::STATUS_ACTIVE,
            'published_at' => $date,
            'expires_at' => $date->copy()->addDays(15),
        ]);
    }

    public function reject($reason)
    {
        $this->update([
            'status' => self::STATUS_DRAFT,
            'reject_reason' => $reason,
        ]);
    }

    public function scopeForUser($query, User $user){
        return $query->where('user_id', $user->id);
    }
}
