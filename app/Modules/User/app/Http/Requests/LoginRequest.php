<?php

namespace Modules\User\Http\Requests;

use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
        ];
    }
}
