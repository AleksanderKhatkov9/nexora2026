export async function fetchHomePage() {
    const { data } = await window.axios.get('/api/page/home');
    return data.data;
}

export async function fetchPricingPage() {
    const { data } = await window.axios.get('/api/page/pricing');
    return data.data;
}

export async function fetchNavigation() {
    const { data } = await window.axios.get('/api/page/navigation');
    return data.data;
}

export async function fetchPageBySlug(slug) {
    const { data } = await window.axios.get(`/api/page/${slug}`);
    return data.data;
}

export async function fetchProjectsPortfolio(tag = null) {
    const params = tag && tag !== 'all' ? { tag } : {};
    const { data } = await window.axios.get('/api/projects', { params });
    return data.data;
}


export async function fetchProjectShow(id) {
    const { data } = await window.axios.get(`/api/projects/${id}`);
    return data.data;
}

export async function submitOrder(payload) {
    const { data } = await window.axios.post('/api/orders', payload);
    return data;
}