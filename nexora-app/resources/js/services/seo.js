export function applyPageSeo(page, options = {}) {
    if (!page) {
        return;
    }

    const title = page.seo_title || page.title;
    const description = page.seo_description || page.description || '';
    const canonical = options.canonical || buildCanonicalUrl();
    const image = page.image || page.cover_image || options.defaultImage || getDefaultOgImage();
    const siteName = options.siteName || 'Nexora';

    if (title) {
        document.title = title;
    }

    setMetaName('description', description);
    setLinkRel('canonical', canonical);

    setMetaProperty('og:title', title);
    setMetaProperty('og:description', description);
    setMetaProperty('og:url', canonical);
    setMetaProperty('og:type', options.ogType || 'website');
    setMetaProperty('og:site_name', siteName);
    setMetaProperty('og:image', image);

    setMetaName('twitter:card', 'summary_large_image');
    setMetaName('twitter:title', title);
    setMetaName('twitter:description', description);
    setMetaName('twitter:image', image);
}

function buildCanonicalUrl() {
    const { origin, pathname } = window.location;

    return `${origin}${pathname}`;
}

function getDefaultOgImage() {
    const root = document.getElementById('app');

    return root?.dataset.defaultOgImage || '';
}

function setMetaName(name, content) {
    if (!content) {
        return;
    }

    upsertMeta('meta', 'name', name, content);
}

function setMetaProperty(property, content) {
    if (!content) {
        return;
    }

    upsertMeta('meta', 'property', property, content);
}

function setLinkRel(rel, href) {
    if (!href) {
        return;
    }

    let element = document.querySelector(`link[rel="${rel}"]`);

    if (!element) {
        element = document.createElement('link');
        element.setAttribute('rel', rel);
        document.head.appendChild(element);
    }

    element.setAttribute('href', href);
}

function upsertMeta(tagName, attribute, key, content) {
    let element = document.querySelector(`${tagName}[${attribute}="${key}"]`);

    if (!element) {
        element = document.createElement(tagName);
        element.setAttribute(attribute, key);
        document.head.appendChild(element);
    }

    element.setAttribute('content', content);
}
