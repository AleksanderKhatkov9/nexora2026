<header class="landing-header">
    <div class="landing-container">
        <div class="landing-header__inner">
            <a href="{{ route('home') }}" class="landing-logo">
                <img src="{{ asset('favicon.svg') }}" alt="" class="landing-logo__icon" width="32" height="32">
                Nex<span>ora</span>
            </a>

            <nav class="landing-nav" aria-label="Основное меню">
                <a href="{{ route('home') }}#services">Услуги</a>
                <a href="{{ route('projects') }}" @class(['is-active' => request()->routeIs('projects')])>Портфолио</a>
            </nav>

            <div class="landing-header__actions">
                <a href="tel:+375291234567" class="landing-phone">+375 (29) 123-45-67</a>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/nova') }}" class="landing-auth">Панель</a>
                    @else
                        <a href="{{ route('login') }}" class="landing-auth">Вход</a>
                    @endauth
                @endif

                <a href="{{ route('home') }}#contact" class="landing-btn landing-btn--primary">Написать напрямую</a>

                <button type="button" class="landing-burger" aria-label="Меню" aria-expanded="false" data-burger>
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>

        <nav class="landing-mobile-nav" data-mobile-nav aria-label="Мобильное меню">
            <a href="{{ route('home') }}#services">Услуги</a>
            <a href="{{ route('projects') }}" @class(['is-active' => request()->routeIs('projects')])>Портфолио</a>
            <a href="tel:+375291234567">+375 (29) 123-45-67</a>
        </nav>
    </div>
</header>
