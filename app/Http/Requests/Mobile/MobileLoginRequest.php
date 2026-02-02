<?php

namespace App\Http\Requests\Mobile;

use Illuminate\Foundation\Http\FormRequest;

final class MobileLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pin' => ['required', 'string', 'size:4', 'regex:/^[0-9]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'pin.required' => 'A PIN kód megadása kötelező.',
            'pin.size' => 'A PIN kódnak 4 számjegyből kell állnia.',
            'pin.regex' => 'A PIN kód csak számokat tartalmazhat.',
        ];
    }
}
