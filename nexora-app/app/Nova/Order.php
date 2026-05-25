<?php

namespace App\Nova;

use App\Models\Order as OrderModel;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;

class Order extends Resource
{
    public static $model = OrderModel::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'phone', 'email',
    ];

    public static function label(): string
    {
        return 'Заявки';
    }

    public static function singularLabel(): string
    {
        return 'Заявка';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Имя', 'name')->rules('required', 'max:255'),
            Text::make('Телефон', 'phone')->rules('required', 'max:50'),
            Text::make('Email', 'email')->nullable()->rules('nullable', 'email', 'max:255'),

            Textarea::make('Сообщение', 'message')->nullable(),

            Select::make('Статус', 'status')
                ->options(OrderModel::statuses())
                ->displayUsingLabels()
                ->default(OrderModel::STATUS_NEW)
                ->rules('required'),

            DateTime::make('Создана', 'created_at')->exceptOnForms()->sortable(),
            DateTime::make('Обновлена', 'updated_at')->exceptOnForms()->sortable(),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
