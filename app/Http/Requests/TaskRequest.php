<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'TaskRequest',
    description: 'Task request',
    properties: [
        new Property(
            property: 'title',
            description: 'Task title',
            type: 'string',
            example: 'user@test.com'
        ),
        new Property(
            property: 'description',
            description: 'Task Description',
            type: 'string',
            example: 'password',
        ),

        new Property(
            property: 'status',
            description: 'Task Status',
            type: 'enum',
            enum: ['pending', 'in_progress', 'completed'],
            example: 'completed',
        ),
    ]
)]
class TaskRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
        ];
    }
}
