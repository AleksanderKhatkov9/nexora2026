@extends('layouts.error')

@section('title', '404 — страница не найдена | Nexora')
@section('meta_description', 'Запрошенная страница не найдена. Вернитесь на главную или перейдите в портфолио Nexora.')

@section('content')
    <section class="error-page error-page--404" aria-labelledby="error-title">
        <div class="error-page__bg" aria-hidden="true">
            <span class="error-page__orb error-page__orb--1"></span>
            <span class="error-page__orb error-page__orb--2"></span>
            <span class="error-page__grid"></span>
        </div>

        <div class="landing-container error-page__inner">
            <p class="error-page__code">404</p>

            <div class="error-page__card">
                <span class="error-page__badge">Страница не найдена</span>
                <h1 id="error-title" class="error-page__title">Здесь пока пусто</h1>
                <p class="error-page__text">
                    Ссылка могла устареть, адрес введён с ошибкой или страница была удалена.
                    Проверьте URL или вернитесь на главную.
                </p>

                <div class="error-page__actions">
                    <a href="{{ url('/') }}" class="landing-btn landing-btn--primary">На главную</a>
                    <a href="{{ url('/projects') }}" class="landing-btn landing-btn--outline">Портфолио</a>
                </div>

                <ul class="error-page__links">
                    <li><a href="{{ url('/pricing') }}">Тарифы</a></li>
                    <li><a href="{{ url('/news') }}">Новости</a></li>
                    <li><a href="{{ url('/') }}#contact">Связаться с нами</a></li>
                </ul>
            </div>
        </div>
    </section>
@endsection
