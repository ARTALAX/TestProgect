<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\Product\Database\Factories\ProductFactory;

// use Modules\Product\Database\Factories\ProductFactory;

/**
 * @property int         $id
 * @property string      $name
 * @property null|string $description
 * @property float       $price
 * @property null|float  $weight
 * @property string      $category
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 *
 * @method static \Modules\Product\Database\Factories\ProductFactory    factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereWeight($value)
 *
 * @mixin \Eloquent
 */
class Product extends Model
{
    /**
     * @use HasFactory<\Modules\Product\Database\Factories\ProductFactory>
     */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'weight',
        'category', ];

    protected $casts = [
        'price' => 'float',
        'weight' => 'float',
    ];

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }
}
