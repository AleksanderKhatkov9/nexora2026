<script setup>
import { inject } from 'vue';
import Header from './Layouts/Header.vue';
import Footer from './Layouts/Footer.vue';

const appConfig = inject('appConfig');
</script>

<template>
    <Header v-bind="appConfig" />
    <router-view />
    <Footer :current-year="appConfig.currentYear" />
</template>
