<?php

declare(strict_types=1);

namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'UserResource',
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
            property: 'email',
            description: 'User Email',
            type: 'string',
            example: 'user@test.com'
        ),
        new Property(
            property: 'created_at',
            description: 'Date of Creation',
            type: 'string',
            example: '2025-05-25 17:14:30'
        ),
        new Property(
            property: 'updated_at',
            description: 'Date of Update',
            type: 'string',
            example: '2025-05-25 17:14:30'
        ),
    ]
)]
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->resource->id,
            'name'          => $this->resource->name,
            'email'         => $this->resource->email,
            'created_at'    => $this->resource->created_at?->format('Y-m-d H:i:s'),
            'updated_at'    => $this->resource->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
