<?php

namespace App\Providers;

use App\Models\ApiIntegration;
use App\Observers\ApiIntegrationObserver;
use App\Repositories\Contracts\BlogPostRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PageRepositoryInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\BlogPostRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PageRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\TagRepository;
use App\Services\Integrations\IntegrationManager;
use App\Services\YandexWebmaster\YandexWebmasterClient;
use App\Services\YandexWebmaster\YandexWebmasterService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        $this->app->bind(BlogPostRepositoryInterface::class, BlogPostRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);

        $this->app->singleton(IntegrationManager::class);

        $this->app->singleton(YandexWebmasterClient::class, function ($app) {
            return new YandexWebmasterClient(
                integrations: $app->make(IntegrationManager::class),
                baseUrl: (string) config('yandex.webmaster.api_base'),
            );
        });

        $this->app->singleton(YandexWebmasterService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ApiIntegration::observe(ApiIntegrationObserver::class);
    }
}
