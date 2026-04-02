<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\User\Http\Requests\UpdateUserRoleRequest;
use Modules\User\Models\User;
use Modules\User\Services\UserAdminService;

class AdminUserController extends Controller
{
    public function __construct(private readonly UserAdminService $service) {}

    public function index(): JsonResponse
    {
        $perPage = (int) request(key: 'per_page', default: 15);

        return response()->json($this->service->paginate(perPage: max($perPage, 1)));
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user): JsonResponse
    {
        $updatedUser = $this->service->updateRole(user: $user, role: $request->validated(key: 'role'));

        return response()->json($updatedUser);
    }
}
