<?php

namespace Modules\CartItem\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Cart\Models\Cart;
use Modules\CartItem\Database\Factories\CartItemFactory;
use Modules\Product\Models\Product;

// use Modules\CartItem\Database\Factories\CartItemFactory;
/**
 * @property int         $id
 * @property int         $cart_id
 * @property int         $product_id
 * @property int         $quantity
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property Cart        $cart
 * @property Product     $product
 *
 * @method static \Modules\CartItem\Database\Factories\CartItemFactory   factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class CartItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(related: Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(related: Product::class);
    }

    protected static function newFactory(): CartItemFactory
    {
        return CartItemFactory::new();
    }
}
