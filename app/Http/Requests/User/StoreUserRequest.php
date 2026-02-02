<?php

namespace App\Http\Requests\User;

use App\Domain\DTOs\User\UserDTO;
use App\Domain\Enums\UserStatusEnum;
use App\Domain\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        //return (bool) $this->user();
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'string', 'min:10', 'max:255'],

            'type' => ['required', 'string', Rule::in(array_map(fn($c) => $c->value, UserTypeEnum::cases()))],
            'status' => ['required', 'string', Rule::in(array_map(fn($c) => $c->value, UserStatusEnum::cases()))],

            'partner_id' => ['nullable', 'integer', 'min:1'],
            'worker_id' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function toDto(): UserDTO
    {
        $type = UserTypeEnum::tryFrom($this->string('type')->toString());
        $status = UserStatusEnum::tryFrom($this->string('status')->toString());

        if (!$type) {
            abort(422, 'Invalid user type');
        }

        if (!$status) {
            abort(422, 'Invalid user status');
        }

        return new UserDTO(
            first_name: $this->string('first_name')->toString(),
            last_name: $this->string('last_name')->toString(),
            email: mb_strtolower($this->string('email')->toString()),
            password: $this->string('password')->toString(),
            type: $type,
            status: $status,
            partner_id: $this->filled('partner_id') ? (int) $this->input('partner_id') : null,
            worker_id: $this->filled('worker_id') ? (int) $this->input('worker_id') : null,
        );
    }
}
