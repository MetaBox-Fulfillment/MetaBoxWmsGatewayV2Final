<?php

namespace App\Http\Requests\User;

use App\Domain\DTOs\User\LoginDTO;
use Illuminate\Foundation\Http\FormRequest;

final class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }

    public function toDto(): LoginDTO
    {
        return new LoginDTO(
            email: mb_strtolower($this->string('email')->toString()),
            password: $this->string('password')->toString(),
            remember: $this->boolean('remember'),
        );
    }
}
