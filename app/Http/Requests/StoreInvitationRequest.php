<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/u'],
            'email' => ['required', 'email'],
            'role'  => ['required', 'in:admin,member'],
            'company' => [
                    Rule::requiredIf(fn () => $this->user()?->role_id === 1),
                    'numeric',
                ],
        ];
    }
}
