export async function fetchHomePage() {
    const { data } = await window.axios.get('/api/page/home');
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