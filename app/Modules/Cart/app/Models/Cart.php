<?php

namespace Modules\Cart\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Cart\Database\Factories\CartFactory;
use Modules\CartItem\Models\CartItem;

// use Modules\Cart\Database\Factories\CartFactory;

/**
 * @property int                                                     $id
 * @property null|int                                                $user_id
 * @property null|Carbon                                             $created_at
 * @property null|Carbon                                             $updated_at
 * @property \Illuminate\Database\Eloquent\Collection<int, CartItem> $items
 * @property null|int                                                $items_count
 *
 * @method static \Modules\Cart\Database\Factories\CartFactory       factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(related: CartItem::class);
    }

    public function total(): float
    {
        /** @var Collection<int, CartItem> $items */
        $items = $this->items;

        return (float) $items->sum(callback: fn (CartItem $item) => $item->quantity * $item->product->price);
    }

    protected static function newFactory(): CartFactory
    {
        return CartFactory::new();
    }
}
