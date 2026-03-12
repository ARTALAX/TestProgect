<?php

namespace Modules\Address\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Address\Database\Factories\AddressFactory;
use Modules\Order\Models\Order;
use Modules\User\Models\User;

// use Modules\Address\Database\Factories\AddressFactory;

/**
 * @property int                    $id
 * @property int                    $user_id
 * @property string                 $region
 * @property string                 $city
 * @property string                 $street
 * @property string                 $house
 * @property null|string            $entrance
 * @property null|string            $apartment
 * @property string                 $postcode
 * @property null|Carbon            $created_at
 * @property null|Carbon            $updated_at
 * @property Collection<int, Order> $orders
 * @property null|int               $orders_count
 * @property User                   $user
 *
 * @method static \Modules\Address\Database\Factories\AddressFactory    factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereApartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereEntrance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereHouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id', 'region', 'city', 'street', 'house', 'entrance', 'apartment', 'postcode',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(related: Order::class);
    }

    protected static function newFactory(): AddressFactory
    {
        return AddressFactory::new();
    }
}
