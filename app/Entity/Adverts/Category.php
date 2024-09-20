<?php

namespace App\Entity\Adverts;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @property string $name
 * @property string $slug
 * @property int $parent_id
 *
 * @property Attribute[] $attributes
 * @property Category $parent
 * @property Category[] $children
 * @property int $depth
 * @property array $allAttributes
 * @property array $attributes
 * @property array $parentAttributes
 */
class Category extends Model
{
    use NodeTrait;

    protected $table = 'advert_categories';

    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'parent_id'];

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'category_id', 'id');
    }

    public function parentAttributes()
    {
        return  $this->parent ? $this->parent->allAttributes() : [];
    }

    public function allAttributes()
    {
        $parent = $this->parentAttributes();
        $owm = $this->attributes()->orderBy('sort')->getModels();
        return array_merge($parent, $owm);
    }
}
