<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Address\Models\Address;
use Modules\Order\Database\Factories\OrderFactory;
use Modules\Order\Enums\OrderStatus;
use Modules\OrderItem\Models\OrderItem;
use Modules\User\Models\User;

// use Modules\Order\Database\Factories\OrderFactory;

/**
 * @property int                        $id
 * @property null|int                   $user_id
 * @property int                        $address_id
 * @property string                     $status
 * @property numeric                    $total_price
 * @property null|Carbon                $created_at
 * @property null|Carbon                $updated_at
 * @property Address                    $address
 * @property Collection<int, OrderItem> $items
 * @property null|int                   $items_count
 * @property null|User                  $user
 *
 * @method static \Modules\Order\Database\Factories\OrderFactory      factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'status',
        'address_id',
        'total_price',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function cancel(): void
    {
        if ($this->status === OrderStatus::COMPLETED->value) {
            throw new \RuntimeException(message: 'Невозможно отменить завершенный заказ');
        }

        $this->status = OrderStatus::CANCELLED->value;
        $this->save();
    }

    public function items(): HasMany
    {
        return $this->hasMany(related: OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(related: Address::class);
    }

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }
}
