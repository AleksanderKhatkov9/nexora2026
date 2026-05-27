<script setup>
import { useAppConfig } from '../composables/useInjections.js';
import Header from './Layouts/Header.vue';
import Footer from './Layouts/Footer.vue';

const appConfig = useAppConfig();
</script>

<template>
    <Header v-bind="appConfig" />
    <router-view />
    <Footer :current-year="appConfig.currentYear" />
</template>
