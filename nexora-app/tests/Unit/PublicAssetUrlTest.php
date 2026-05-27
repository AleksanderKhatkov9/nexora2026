<?php

namespace Tests\Unit;

use App\Support\PublicAssetUrl;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicAssetUrlTest extends TestCase
{
    public function test_it_builds_public_storage_url(): void
    {
        Storage::fake('public');

        $url = PublicAssetUrl::url('projects/cover.jpg');

        $this->assertSame(Storage::disk('public')->url('projects/cover.jpg'), $url);
    }

    public function test_it_returns_null_for_empty_path(): void
    {
        $this->assertNull(PublicAssetUrl::url(null));
        $this->assertNull(PublicAssetUrl::url(''));
    }
}
