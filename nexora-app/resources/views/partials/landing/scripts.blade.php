<script>
    (function () {
        const burger = document.querySelector('[data-burger]');
        const mobileNav = document.querySelector('[data-mobile-nav]');
        if (!burger || !mobileNav) return;

        burger.addEventListener('click', function () {
            const open = mobileNav.classList.toggle('is-open');
            burger.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        mobileNav.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                mobileNav.classList.remove('is-open');
                burger.setAttribute('aria-expanded', 'false');
            });
        });
    })();
</script>
