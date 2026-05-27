<script setup>
defineProps({
    loading: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        default: '',
    },
    loadingText: {
        type: String,
        default: 'Загрузка…',
    },
});
</script>

<template>
    <div v-if="loading" class="landing-container landing-state">
        <p>{{ loadingText }}</p>
    </div>

    <div v-else-if="error" class="landing-container landing-state landing-state--error">
        <p>{{ error }}</p>
        <slot name="error-actions" />
    </div>

    <slot v-else />
</template>

<style scoped>
.landing-state {
    padding: 48px 0;
    text-align: center;
    color: var(--nx-text-muted);
}

.landing-state--error {
    color: #b42318;
}
</style>
