<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_feed_returns_active_posts(): void
    {
        BlogPost::query()->create([
            'slug' => 'test-news',
            'title' => 'Test News',
            'kind' => BlogPost::KIND_NEWS,
            'excerpt' => 'Excerpt',
            'active' => true,
            'published_at' => now(),
        ]);

        BlogPost::query()->create([
            'slug' => 'draft-news',
            'title' => 'Draft',
            'kind' => BlogPost::KIND_NEWS,
            'active' => false,
            'published_at' => now(),
        ]);

        $response = $this->getJson('/api/news');

        $response->assertOk();
        $response->assertJsonCount(1, 'data.posts');
        $response->assertJsonPath('data.posts.0.slug', 'test-news');
    }

    public function test_articles_feed_returns_active_posts(): void
    {
        BlogPost::query()->create([
            'slug' => 'test-article',
            'title' => 'Test Article',
            'kind' => BlogPost::KIND_ARTICLE,
            'content' => 'Full text',
            'active' => true,
            'published_at' => now(),
        ]);

        $response = $this->getJson('/api/articles');

        $response->assertOk();
        $response->assertJsonPath('data.posts.0.slug', 'test-article');
    }

    public function test_news_post_can_be_fetched_by_slug(): void
    {
        BlogPost::query()->create([
            'slug' => 'launch',
            'title' => 'Launch',
            'kind' => BlogPost::KIND_NEWS,
            'content' => 'Body',
            'seo_title' => 'Launch SEO',
            'active' => true,
            'published_at' => now(),
        ]);

        $response = $this->getJson('/api/news/launch');

        $response->assertOk();
        $response->assertJsonPath('data.slug', 'launch');
        $response->assertJsonPath('data.content', 'Body');
    }

    public function test_article_slug_on_news_route_returns_404(): void
    {
        BlogPost::query()->create([
            'slug' => 'only-article',
            'title' => 'Article',
            'kind' => BlogPost::KIND_ARTICLE,
            'active' => true,
            'published_at' => now(),
        ]);

        $this->getJson('/api/news/only-article')->assertNotFound();
    }

    public function test_news_page_is_available(): void
    {
        $this->get('/news')->assertOk();
        $this->get('/articles')->assertOk();
    }

    public function test_news_post_page_renders_server_side_seo(): void
    {
        BlogPost::query()->create([
            'slug' => 'seo-news',
            'title' => 'SEO News',
            'kind' => BlogPost::KIND_NEWS,
            'excerpt' => 'SEO excerpt',
            'seo_title' => 'SEO News Title',
            'seo_description' => 'SEO News Description',
            'active' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/news/seo-news');

        $response->assertOk();
        $response->assertSee('<title>SEO News Title</title>', false);
        $response->assertSee('rel="canonical" href="'.url('/news/seo-news').'"', false);
    }
}
