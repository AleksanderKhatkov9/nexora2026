import { computed, onMounted } from 'vue';
import { applyPageSeo } from '../services/seo.js';
import { useAsyncResource } from './useAsyncResource.js';
import { useSiteApi } from './useInjections.js';

export function useBlogFeed(kind) {
    const api = useSiteApi();

    const resource = useAsyncResource(
        () => (kind === 'news' ? api.blog.getNewsFeed() : api.blog.getArticlesFeed()),
        {
            errorMessage: kind === 'news'
                ? 'Не удалось загрузить новости.'
                : 'Не удалось загрузить статьи.',
            onSuccess: (data) => applyPageSeo(data.page),
        },
    );

    const posts = computed(() => resource.data.value?.posts ?? []);
    const pageMeta = computed(() => resource.data.value?.page ?? null);

    onMounted(() => resource.execute());

    return {
        loading: resource.loading,
        error: resource.error,
        posts,
        pageMeta,
        load: resource.execute,
    };
}
