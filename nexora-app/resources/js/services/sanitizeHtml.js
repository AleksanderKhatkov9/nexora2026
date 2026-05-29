const ALLOWED_TAGS = new Set([
    'a',
    'b',
    'blockquote',
    'br',
    'code',
    'em',
    'h2',
    'h3',
    'h4',
    'hr',
    'i',
    'li',
    'ol',
    'p',
    'pre',
    'strong',
    'ul',
]);

const ALLOWED_ATTRIBUTES = {
    a: new Set(['href', 'title', 'target', 'rel']),
};

const ALLOWED_PROTOCOLS = new Set(['http:', 'https:', 'mailto:', 'tel:']);

export function sanitizeHtml(value) {
    if (!value || typeof window === 'undefined' || typeof DOMParser === 'undefined') {
        return '';
    }

    const documentFragment = new DOMParser().parseFromString(String(value), 'text/html');
    sanitizeChildren(documentFragment.body);

    return documentFragment.body.innerHTML;
}

function sanitizeChildren(parent) {
    [...parent.children].forEach((element) => {
        const tagName = element.tagName.toLowerCase();

        if (!ALLOWED_TAGS.has(tagName)) {
            element.replaceWith(...element.childNodes);
            return;
        }

        sanitizeAttributes(element, tagName);
        sanitizeChildren(element);
    });
}

function sanitizeAttributes(element, tagName) {
    [...element.attributes].forEach((attribute) => {
        const name = attribute.name.toLowerCase();
        const allowedForTag = ALLOWED_ATTRIBUTES[tagName];

        if (!allowedForTag?.has(name)) {
            element.removeAttribute(attribute.name);
            return;
        }

        if (name === 'href' && !isSafeUrl(attribute.value)) {
            element.removeAttribute(attribute.name);
        }
    });

    if (tagName === 'a') {
        element.setAttribute('rel', 'noopener noreferrer');
    }
}

function isSafeUrl(value) {
    try {
        const url = new URL(value, window.location.origin);

        return ALLOWED_PROTOCOLS.has(url.protocol);
    } catch {
        return false;
    }
}
