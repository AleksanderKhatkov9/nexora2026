import { ref } from 'vue';

export function useAsyncResource(loader, options = {}) {
    const loading = ref(false);
    const error = ref('');
    const data = ref(null);

    const execute = async (...args) => {
        loading.value = true;
        error.value = '';

        try {
            const result = await loader(...args);
            data.value = result;
            options.onSuccess?.(result);
            return result;
        } catch (cause) {
            if (cause?.response?.status === 404) {
                error.value = resolveOption(options.notFoundMessage, 'Страница не найдена.');
            } else {
                error.value = resolveOption(options.errorMessage, 'Не удалось загрузить данные.');
            }

            data.value = null;
            console.error(cause);
            throw cause;
        } finally {
            loading.value = false;
        }
    };

    return {
        loading,
        error,
        data,
        execute,
    };
}

function resolveOption(value, fallback) {
    return (typeof value === 'function' ? value() : value) || fallback;
}
