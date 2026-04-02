<?php

namespace Modules\User\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\User\Enums\UserRole;

class UpdateUserRoleRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'role' => ['required', new Enum(type: UserRole::class)],
        ];
    }
}
