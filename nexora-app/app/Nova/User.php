<?php

namespace App\Nova;

use Laravel\Nova\Auth\PasswordValidationRules;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    use PasswordValidationRules;

    public static $model = \App\Models\User::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'email',
    ];

    public static function label(): string
    {
        return 'Пользователи';
    }

    public static function singularLabel(): string
    {
        return 'Пользователь';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Gravatar::make()->maxWidth(50),

            Text::make('Имя', 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email', 'email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            BelongsTo::make('Роль', 'userRole', UserRole::class)
                ->nullable()
                ->sortable()
                ->rules('required'),

            Password::make('Пароль', 'password')
                ->onlyOnForms()
                ->creationRules($this->passwordRules())
                ->updateRules($this->optionalPasswordRules()),
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
