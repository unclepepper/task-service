<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'UserRequest',
    description: 'Registration request',
    properties: [
        new Property(
            property: 'name',
            description: 'User Name',
            type: 'string',
            example: 'Ivan',
        ),
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
        new Property(
            property: 'role',
            description: 'User role',
            type: 'integer',
            example: 2,
        ),

    ]
)]
class UserRequest extends FormRequest
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
            'name'      => 'required|string|required|max:50',
            'email'     => 'required|string|email|required|max:50|unique:users',
            'password'  => 'required|string|required|min:3',
            'role'      => 'nullable|integer',
        ];
    }
}
