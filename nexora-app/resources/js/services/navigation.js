export function resolveNavigationLink(item) {
    if (item.type === 'anchor') {
        return { name: 'home', hash: item.hash };
    }

    if (item.type === 'external') {
        return item.external_url;
    }

    if (item.route_name === 'page') {
        return { name: 'page', params: { slug: item.slug } };
    }

    return { name: item.route_name };
}

export function isNavigationItemActive(route, item) {
    if (item.type === 'anchor') {
        return route.name === 'home' && route.hash === item.hash;
    }

    if (item.type === 'external') {
        return false;
    }

    if (item.route_name === 'projects') {
        return route.name === 'projects' || route.name === 'projects.view';
    }

    if (item.route_name === 'page') {
        return route.name === 'page' && route.params.slug === item.slug;
    }

    return route.name === item.route_name;
}
