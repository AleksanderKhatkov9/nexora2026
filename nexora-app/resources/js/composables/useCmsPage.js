import { onMounted, watch } from 'vue';
import { applyPageSeo } from '../services/seo.js';
import { useAsyncResource } from './useAsyncResource.js';

export function useCmsPage(loader, options = {}) {
    const { immediate = true, watchSource, ...resourceOptions } = options;

    const resource = useAsyncResource(loader, {
        ...resourceOptions,
        onSuccess: applyPageSeo,
    });

    if (watchSource) {
        watch(watchSource, () => resource.execute(), { immediate });
    } else if (immediate) {
        onMounted(() => resource.execute());
    }

    return {
        loading: resource.loading,
        error: resource.error,
        page: resource.data,
        reload: resource.execute,
    };
}
