<?php

namespace App\Http\Requests\Mobile;

use App\Models\User\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

final class SetPinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pin' => ['required', 'string', 'size:4', 'regex:/^[0-9]+$/'],
            'pin_confirmation' => ['required', 'string', 'same:pin'],
        ];
    }

    public function messages(): array
    {
        return [
            'pin.required' => 'A PIN kód megadása kötelező.',
            'pin.size' => 'A PIN kódnak 4 számjegyből kell állnia.',
            'pin.regex' => 'A PIN kód csak számokat tartalmazhat.',
            'pin_confirmation.required' => 'A PIN kód megerősítése kötelező.',
            'pin_confirmation.same' => 'A két PIN kód nem egyezik.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $pin = $this->string('pin')->toString();

                // Ellenőrizzük, hogy nincs-e már ilyen PIN kód
                $existingUser = User::whereNotNull('pin_code')
                    ->where('id', '!=', $this->user()->id)
                    ->get()
                    ->first(function (User $user) use ($pin) {
                        return Hash::check($pin, $user->pin_code);
                    });

                if ($existingUser) {
                    $validator->errors()->add('pin', 'Ez a PIN kód már használatban van.');
                }
            },
        ];
    }
}
