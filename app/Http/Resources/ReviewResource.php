<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Http\Resources\AutherResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $reviewableEntity = $this->reviewable;
        $reviewableResource = $reviewableEntity instanceof \App\Models\Book
                            ? new BookResource($reviewableEntity)
                            : new AutherResource($reviewableEntity);
        return [
            'review' => $this->review,
            'reviewable' => [
                'type' => class_basename($this->reviewable_type),
                'details' => $reviewableResource,
            ]
        ];
    }
}
