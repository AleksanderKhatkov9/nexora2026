export function createHttpClient(axiosInstance) {
    return {
        async get(url, config = {}) {
            const { data } = await axiosInstance.get(url, config);
            return data.data ?? data;
        },

        async post(url, body, config = {}) {
            const { data } = await axiosInstance.post(url, body, config);
            return data;
        },
    };
}
