<?php

namespace App\Providers;

use App\Models\User;
use App\Nova\Dashboards\Main;
use App\Nova\Dashboards\OrdersAnalytics;
use App\Nova\User as NovaUser;
use App\Nova\Order as NovaOrder;
use App\Nova\Page as NovaPage;
use App\Nova\Project as NovaProject;
use App\Nova\Tags as NovaTags;
use App\Nova\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Features;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        Nova::mainMenu(function (Request $request) {
            return [
                MenuSection::dashboard(Main::class)->icon('home'),
                MenuSection::dashboard(OrdersAnalytics::class)->icon('chart-bar'),
                
                MenuSection::make('Пользователи', [
                    MenuItem::resource(NovaUser::class),
                    MenuItem::resource(UserRole::class),
                ])->icon('user-group')->collapsable(),

                MenuSection::make('Контент', [
                    MenuItem::resource(NovaPage::class),
                    MenuItem::resource(NovaTags::class),
                    MenuItem::resource(NovaProject::class),
                    MenuItem::resource(NovaOrder::class),
                ])->icon('document-text')->collapsable(),

            ];
        });



        //
    }

    /**
     * Register the configurations for Laravel Fortify.
     */
    protected function fortify(): void
    {
        Nova::fortify()
            ->features([
                Features::updatePasswords(),
                // Features::emailVerification(),
                // Features::twoFactorAuthentication(['confirm' => true, 'confirmPassword' => true]),
            ])
            ->register();
    }

    /**
     * Register the Nova routes.
     */
    protected function routes(): void
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->withoutEmailVerificationRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     */
    protected function gate(): void
    {
        // Gate::define('viewNova', function (User $user) {
        //     return in_array($user->email, [
        //         //
        //     ]);
        // });

        Gate::define('viewNova', function (User $user) {
            // Доступ к Nova: те же записи `users`, что и для сайта (guard `web`).
            return $user->isAdmin();
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array<int, \Laravel\Nova\Dashboard>
     */
    protected function dashboards(): array
    {
        return [
            new Main,
            new OrdersAnalytics,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array<int, \Laravel\Nova\Tool>
     */
    public function tools(): array
    {
        return [];
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        parent::register();

        //
    }
}
