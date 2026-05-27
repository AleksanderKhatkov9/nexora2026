<?php

namespace App\Repositories;

use App\Models\Tags;
use App\Repositories\Contracts\TagRepositoryInterface;
use Illuminate\Support\Collection;

class TagRepository implements TagRepositoryInterface
{
    public function getAll(): Collection
    {
        return Tags::query()
            ->orderBy('name')
            ->get();
    }
}
