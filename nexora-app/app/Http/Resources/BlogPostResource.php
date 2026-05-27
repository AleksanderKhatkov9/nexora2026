<?php

namespace App\Http\Resources;

use App\Support\PublicAssetUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogPostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'kind' => $this->kind,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => $this->when($this->content, $this->content),
            'cover_image' => PublicAssetUrl::url($this->cover_image),
            'author' => $this->author,
            'published_at' => $this->published_at?->toIso8601String(),
            'published_at_formatted' => $this->published_at?->translatedFormat('d F Y'),
            'seo_title' => $this->when($this->seo_title, $this->seo_title),
            'seo_description' => $this->when($this->seo_description, $this->seo_description),
        ];
    }
}
