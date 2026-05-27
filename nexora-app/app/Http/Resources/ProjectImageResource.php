<?php

namespace App\Http\Resources;

use App\Support\PublicAssetUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'path' => PublicAssetUrl::url($this->path),
            'alt' => $this->alt,
            'sort_order' => $this->sort_order,
        ];
    }
}
