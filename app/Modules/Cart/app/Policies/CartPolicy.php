<?php

namespace Modules\Cart\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\CartItem\Models\CartItem;
use Modules\User\Models\User;

class CartPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function delete(User $user, CartItem $item): bool
    {
        return $item->cart->user_id === $user->id;
    }

    public function update(User $user, CartItem $item): bool
    {
        return $item->cart->user_id === $user->id;
    }
}
