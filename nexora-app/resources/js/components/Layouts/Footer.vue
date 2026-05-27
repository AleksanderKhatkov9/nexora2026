<script setup>
defineProps({
    currentYear: {
        type: [String, Number],
        default: () => new Date().getFullYear(),
    },
});
</script>

<template>
    <footer class="landing-footer">
        <div class="landing-container">
            <p>&copy; {{ currentYear }} Nexora. Разработка сайтов и веб-приложений.</p>
        </div>
    </footer>
</template>
