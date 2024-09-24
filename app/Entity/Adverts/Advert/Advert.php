<?php

namespace App\Entity\Adverts\Advert;

use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Entity\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

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
 * @property User $user
 * @property Category $category
 * @property Value[] $values
 *
 * @method static Builder forUser(User $user)
 * @method static Builder forCategory(Category $category)
 * @method static Builder forRegion(Region $region)
 * @method static Builder active()
 * @method expire()
 * @method static statusesList()
 */
class Advert extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_MODERATION = 'moderation';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_EXPIRED = 'expired';

    protected $table = 'advert_adverts';

    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public static function statusesList()
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

    public function getValue($id)
    {
        foreach ($this->values as $value) {
            if ($value->attribute_id == $id) {
                return $value->value;
            }
        }

        return null;
    }

    public function sendToModeration()
    {
        if (!$this->isDraft()) {
            throw new \DomainException('Advert is not a draft.');
        }

        if (!$this->photos()->count()) {
            throw new \DomainException('Upload photos.');
        }

        $this->update([
            'status' => self::STATUS_MODERATION
        ]);
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

    public function expire()
    {
        $this->update([
            'status' => self::STATUS_EXPIRED,
        ]);
    }

    public function close()
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
        ]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeForCategory($query, Category $category)
    {
        return $query->whereIn('category_id', array_merge(
            [$category->id],
            $category->descendants()->pluck('id')->toArray() // children
        ));
    }

    public function scopeForRegion($query, Region $region)
    {
        $ids = [$region->id];
        $childrenIds = $ids;
        while ($childrenIds = Region::where(['parent_id', $childrenIds])->pluck('id')->toArray()) {
            $ids =  array_merge($ids, $childrenIds);
        }
        return $query->whereIn('parent_id', $ids);
    }
}
