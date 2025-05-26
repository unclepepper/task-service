<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

//#[Schema(
//    title: 'UserResource',
//    description: 'Authorization',
//    properties: [
//        new Property(
//            property: 'token',
//            description: 'Authorization token',
//            type: 'string',
//            example: '38|MaXb2t7AYhK5w6r1GpSpJPRYQEHaNZP7vBSIPWjt98361465'
//        )
//    ]
//)]
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
