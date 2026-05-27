import { computed, onMounted, ref } from 'vue';
import { applyPageSeo } from '../services/seo.js';
import { useAsyncResource } from './useAsyncResource.js';
import { useSiteApi } from './useInjections.js';

export function usePortfolio() {
    const api = useSiteApi();
    const activeTag = ref('all');

    const resource = useAsyncResource(
        () => api.projects.getPortfolio(),
        {
            errorMessage: 'Не удалось загрузить портфолио.',
            onSuccess: (data) => applyPageSeo(data.page),
        },
    );

    const tags = computed(() => resource.data.value?.tags ?? []);
    const projects = computed(() => resource.data.value?.projects ?? []);
    const pageMeta = computed(() => resource.data.value?.page ?? null);

    const allTags = computed(() => [
        { slug: 'all', name: 'Все проекты' },
        ...tags.value,
    ]);

    const filteredProjects = computed(() => {
        if (activeTag.value === 'all') {
            return projects.value;
        }

        return projects.value.filter((project) =>
            project.tags?.some((tag) => tag.slug === activeTag.value),
        );
    });

    const selectTag = (slug) => {
        activeTag.value = slug;
    };

    onMounted(() => resource.execute());

    return {
        loading: resource.loading,
        error: resource.error,
        pageMeta,
        allTags,
        filteredProjects,
        activeTag,
        selectTag,
        load: resource.execute,
    };
}
