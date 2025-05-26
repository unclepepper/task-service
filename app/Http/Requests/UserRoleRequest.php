<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'UserRoleRequest',
    description: 'User role request',
    properties: [
        new Property(
            property: 'role',
            description: 'User role',
            type: 'string',
            enum: ['admin', 'user'],
            example: 'admin',
        ),

    ]
)]
class UserRoleRequest extends FormRequest
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
            'role'  => 'required|string|in:admin,user',
        ];
    }
}
