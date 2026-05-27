<?php

namespace App\Http\Resources;

use App\Support\PublicAssetUrl;
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
            'full_description' => $this->when($this->full_description, $this->full_description),
            'cover_image' => PublicAssetUrl::url($this->cover_image),
            'site_url' => $this->site_url,
            'seo_title' => $this->when($this->seo_title, $this->seo_title),
            'seo_description' => $this->when($this->seo_description, $this->seo_description),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'images' => ProjectImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
