<?php

namespace Modules\User\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\User\Enums\UserRole;
use Modules\User\Models\User;

class UserAdminService
{
    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return User::query()
            ->select(['id', 'name', 'email', 'role', 'created_at', 'updated_at'])
            ->orderBy(column: 'id')
            ->paginate(perPage: min($perPage, 100))
        ;
    }

    public function updateRole(User $user, string $role): User
    {
        $user->update(attributes: [
            'role' => UserRole::from(value: $role),
        ]);

        return $user->refresh();
    }
}
