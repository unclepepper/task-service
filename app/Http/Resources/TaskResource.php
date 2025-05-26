<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'TaskResource',
    description: 'Task',
    properties: [
        new Property(
            property: 'id',
            description: 'User ID',
            type: 'integer',
            example: 1,
        ),
        new Property(
            property: 'user_id',
            description: 'User Id',
            type: 'integer',
            example: 8,
        ),
        new Property(
            property: 'title',
            description: 'Task title',
            type: 'string',
            example: 'To-do',
        ),
        new Property(
            property: 'description',
            description: 'Task description',
            type: 'string',
            example: 'Description about task',
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
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id'            => $this->id,
            'user_id'       => $this->user_id,
            'title'         => $this->title,
            'description'   => $this->description,
            'status'        => $this->status,
            'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'    => $this->updated_at->format('Y-m-d H:i:s'),

        ];
    }
}
