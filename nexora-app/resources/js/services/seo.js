export function applyPageSeo(page) {
    if (!page) {
        return;
    }

    const title = page.seo_title || page.title;

    if (title) {
        document.title = title;
    }

    if (page.seo_description) {
        const meta = document.querySelector('meta[name="description"]');

        if (meta) {
            meta.setAttribute('content', page.seo_description);
        }
    }
}
