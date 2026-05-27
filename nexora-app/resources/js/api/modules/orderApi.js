export function createOrderApi(http) {
    return {
        submit: (payload) => http.post('/api/orders', payload),
    };
}
