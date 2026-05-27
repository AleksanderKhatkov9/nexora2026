@extends('layouts.error')

@section('title', '500 — ошибка сервера | Nexora')
@section('meta_description', 'На сервере произошла ошибка. Мы уже работаем над её устранением.')

@section('content')
    <section class="error-page error-page--500" aria-labelledby="error-title">
        <div class="error-page__bg" aria-hidden="true">
            <span class="error-page__orb error-page__orb--1"></span>
            <span class="error-page__orb error-page__orb--2"></span>
            <span class="error-page__grid"></span>
        </div>

        <div class="landing-container error-page__inner">
            <p class="error-page__code">500</p>

            <div class="error-page__card">
                <span class="error-page__badge error-page__badge--danger">Ошибка сервера</span>
                <h1 id="error-title" class="error-page__title">Что-то пошло не так</h1>
                <p class="error-page__text">
                    На нашей стороне произошёл сбой. Попробуйте обновить страницу через минуту
                    или вернитесь на главную — мы уже разбираемся с проблемой.
                </p>

                <div class="error-page__actions">
                    <button type="button" class="landing-btn landing-btn--primary" onclick="window.location.reload()">
                        Обновить страницу
                    </button>
                    <a href="{{ url('/') }}" class="landing-btn landing-btn--outline">На главную</a>
                </div>

                <p class="error-page__hint">
                    Если ошибка повторяется, напишите нам через форму на главной — укажите время и адрес страницы.
                </p>
            </div>
        </div>
    </section>
@endsection
