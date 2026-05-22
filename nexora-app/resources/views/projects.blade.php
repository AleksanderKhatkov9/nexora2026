@extends('layouts.landing')

@section('title', 'Портфолио — Nexora')
@section('meta_description', 'Портфолио Nexora: корпоративные сайты, интернет-магазины, порталы и CRM-решения.')

@section('content')
    <section class="landing-page-head">
        <div class="landing-container">
            <nav class="landing-breadcrumb" aria-label="Хлебные крошки">
                <a href="{{ route('home') }}">Создание сайтов</a>
                <span class="landing-breadcrumb__sep">/</span>
                <span class="landing-breadcrumb__current">Портфолио</span>
            </nav>

            <h1>Портфолио</h1>
            <p class="landing-page-intro">
                Необходимость сайта для успешной компании сегодня не вызывает сомнений. Современный человек,
                которого интересует товар или услуга, ищет информацию в интернете — поэтому качественный
                веб-проект становится стабильным источником клиентов для любого бизнеса.
            </p>
        </div>
    </section>

    <section class="landing-projects-section">
        <div class="landing-container">
            <div class="landing-projects-toolbar" role="tablist" aria-label="Фильтр проектов">
                @foreach ($tags as $tag)
                    <button
                        type="button"
                        class="landing-tag @if ($loop->first) is-active @endif"
                        data-tag="{{ $tag['slug'] }}"
                        role="tab"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                    >
                        {{ $tag['name'] }}
                    </button>
                @endforeach
            </div>

            <p class="landing-projects-empty" data-projects-empty hidden>
                По выбранной категории проектов пока нет.
            </p>

            <div class="landing-projects-grid" data-projects-grid>
                @foreach ($projects as $project)
                    <article
                        class="landing-project-card"
                        data-tag="{{ $project['tag'] }}"
                    >
                        <div class="landing-project-card__thumb" aria-hidden="true">
                            {{ $project['initial'] }}
                        </div>
                        <div class="landing-project-card__body">
                            <div class="landing-project-card__meta">
                                <span class="landing-project-card__type">{{ $project['type'] }}</span>
                                <span class="landing-project-card__year">{{ $project['year'] }}</span>
                            </div>
                            <h2 class="landing-project-card__title">{{ $project['title'] }}</h2>
                            <span class="landing-project-card__link">Смотреть проект →</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    (function () {
        const tags = document.querySelectorAll('.landing-tag[data-tag]');
        const cards = document.querySelectorAll('.landing-project-card[data-tag]');
        const empty = document.querySelector('[data-projects-empty]');
        if (!tags.length || !cards.length) return;

        tags.forEach(function (btn) {
            if (!btn.classList.contains('landing-tag')) return;

            btn.addEventListener('click', function () {
                const filter = btn.getAttribute('data-tag');

                document.querySelectorAll('.landing-tag').forEach(function (t) {
                    const active = t === btn;
                    t.classList.toggle('is-active', active);
                    t.setAttribute('aria-selected', active ? 'true' : 'false');
                });

                let visible = 0;
                cards.forEach(function (card) {
                    const match = filter === 'all' || card.getAttribute('data-tag') === filter;
                    card.classList.toggle('is-hidden', !match);
                    if (match) visible++;
                });

                if (empty) {
                    empty.hidden = visible > 0;
                    empty.classList.toggle('is-visible', visible === 0);
                }
            });
        });
    })();
</script>
@endpush
