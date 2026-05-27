<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'type' => $this->type,
            'year' => $this->year,
            'initial' => $this->initial,
            'short_description' => $this->short_description,
            'cover_image' => $this->cover_image,
            'site_url' => $this->site_url,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
