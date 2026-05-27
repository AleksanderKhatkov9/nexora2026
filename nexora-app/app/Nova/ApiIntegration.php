<?php

namespace App\Nova;

use App\Nova\Actions\TestApiIntegration;
use App\Services\Integrations\IntegrationManager;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class ApiIntegration extends Resource
{
    public static $model = \App\Models\ApiIntegration::class;

    public static $title = 'name';

    public static $search = [
        'name', 'driver', 'slug',
    ];

    public static function label(): string
    {
        return 'API-интеграции';
    }

    public static function singularLabel(): string
    {
        return 'API-интеграция';
    }

    public static function authorizedToCreate(\Illuminate\Http\Request $request): bool
    {
        return false;
    }

    public function authorizedToDelete(\Illuminate\Http\Request $request): bool
    {
        return false;
    }

    public function authorizedToReplicate(\Illuminate\Http\Request $request): bool
    {
        return false;
    }

    public function fields(NovaRequest $request): array
    {
        $driverDefinition = config("integrations.drivers.{$this->driver}", []);
        $resolved = app(IntegrationManager::class)->resolve((string) $this->driver);

        $fields = [
            ID::make()->sortable(),

            Text::make('Сервис', 'name')
                ->readonly()
                ->sortable(),

            Text::make('Драйвер', 'driver')
                ->readonly()
                ->hideFromIndex(),

            Boolean::make('Включено', 'enabled')
                ->help('Активные интеграции используются дашбордами и сервисами сайта.'),

            Badge::make('Статус', 'last_test_status')
                ->map([
                    \App\Models\ApiIntegration::TEST_STATUS_SUCCESS => 'success',
                    \App\Models\ApiIntegration::TEST_STATUS_FAILED => 'danger',
                ])
                ->labels([
                    \App\Models\ApiIntegration::TEST_STATUS_SUCCESS => 'OK',
                    \App\Models\ApiIntegration::TEST_STATUS_FAILED => 'Ошибка',
                ])
                ->nullable(),

            Text::make('Проверка', 'last_test_message')
                ->onlyOnIndex()
                ->displayUsing(fn (?string $value) => $value ? mb_strimwidth($value, 0, 60, '…') : '—'),

            DateTime::make('Проверено', 'last_tested_at')
                ->exceptOnForms(),

            Text::make('Источник настроек', fn () => match ($resolved->source) {
                'database' => 'Nova / БД',
                'env' => '.env (fallback)',
                default => '—',
            })->onlyOnDetail(),
        ];

        if (($driverDefinition['description'] ?? null) !== null) {
            $fields[] = Textarea::make('Описание', fn () => $driverDefinition['description'])
                ->readonly()
                ->alwaysShow()
                ->onlyOnDetail();
        }

        $credentialFields = [];

        foreach ($driverDefinition['credential_fields'] ?? [] as $key => $field) {
            $credentialFields[] = Password::make($field['label'], "credential_{$key}")
                ->onlyOnForms()
                ->help(($field['help'] ?? '').' Оставьте пустым, чтобы не менять сохранённый ключ.')
                ->fillUsing(function (NovaRequest $request, $model, $attribute, $requestAttribute) use ($key) {
                    if ($request->filled($requestAttribute)) {
                        $model->setCredential($key, $request->input($requestAttribute));
                    }
                });
        }

        if ($credentialFields !== []) {
            $fields[] = Panel::make('Ключи доступа', $credentialFields);
        }

        $settingFields = [];

        foreach ($driverDefinition['setting_fields'] ?? [] as $key => $field) {
            $settingFields[] = $this->makeSettingField($key, $field);
        }

        if ($settingFields !== []) {
            $fields[] = Panel::make('Параметры', $settingFields);
        }

        $fields[] = Textarea::make('Результат последней проверки', 'last_test_message')
            ->readonly()
            ->onlyOnDetail();

        return $fields;
    }

    /**
     * @param  array<string, mixed>  $field
     */
    private function makeSettingField(string $key, array $field): Number|Text
    {
        $attribute = "setting_{$key}";
        $help = $field['help'] ?? null;

        $fillUsing = function (NovaRequest $request, $model, $attribute, $requestAttribute) use ($key) {
            if ($request->exists($requestAttribute)) {
                $model->setSetting($key, $request->input($requestAttribute));
            }
        };

        $resolveUsing = fn () => $this->resource?->setting(
            $key,
            $field['default'] ?? null
        );

        if (($field['type'] ?? 'text') === 'number') {
            return Number::make($field['label'], $attribute)
                ->resolveUsing($resolveUsing)
                ->fillUsing($fillUsing)
                ->help($help)
                ->min(0);
        }

        return Text::make($field['label'], $attribute)
            ->resolveUsing($resolveUsing)
            ->fillUsing($fillUsing)
            ->help($help);
    }

    public function actions(NovaRequest $request): array
    {
        return [
            (new TestApiIntegration)->showInline()->withoutConfirmation(),
        ];
    }
}
