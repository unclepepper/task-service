<?php

declare(strict_types=1);

namespace App\Http\Resources;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'UserRoleResource',
    description: 'User',
    properties: [
        new Property(
            property: 'id',
            description: 'User ID',
            type: 'integer',
            example: 1,
        ),
        new Property(
            property: 'name',
            description: 'User Name',
            type: 'string',
            example: 'Ivan',
        ),
        new Property(
            property: 'role',
            description: 'User role',
            type: 'string',
            enum: ['admin', 'user'],
            example: 'admin',
        ),
        new Property(
            property: 'updated_at',
            description: 'Date of Update',
            type: 'string',
            example: '2025-05-25 17:14:30'
        ),
    ]
)]
class UserRoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'role'          => User::ROLES_REVERSE[$this->role],
            'updated_at'    => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
