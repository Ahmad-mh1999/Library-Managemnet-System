<?php

namespace App\Http\Resources;

use App\Models\Auther;
use Illuminate\Http\Request;
use App\Http\Resources\AutherResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title'   => $this->title, 
            'authers' => AutherResource::collection($this->whenLoaded('authers'))
        ];
    }
}
