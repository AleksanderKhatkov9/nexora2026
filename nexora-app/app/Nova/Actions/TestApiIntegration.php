<?php

namespace App\Nova\Actions;

use App\Services\Integrations\IntegrationManager;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class TestApiIntegration extends Action
{
    public $name = 'Проверить подключение';

    public function handle(ActionFields $fields, Collection $models)
    {
        /** @var IntegrationManager $manager */
        $manager = app(IntegrationManager::class);

        foreach ($models as $integration) {
            $result = $manager->test($integration);

            if (! $result->success) {
                return Action::danger($integration->name.': '.$result->message);
            }
        }

        $first = $models->first();
        $message = $first ? $first->fresh()?->last_test_message : 'Готово';

        return Action::message($message ?? 'Подключение успешно.');
    }

    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
