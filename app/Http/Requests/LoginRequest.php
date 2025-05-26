<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'LoginRequest',
    description: 'Authorization request',
    properties: [
        new Property(
            property: 'email',
            description: 'User Email',
            type: 'string',
            example: 'user@test.com'
        ),
        new Property(
            property: 'password',
            description: 'User password',
            type: 'string',
            example: 'password',
        ),
    ]
)]
class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'     => 'required|string|email|max:50|',
            'password'  => 'required|string|required|min:3',
        ];
    }
}
