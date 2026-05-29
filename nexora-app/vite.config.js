import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        watch: {
            usePolling: true,
            interval: 100,
        },
        cors: {
            origin: ['http://nexora.loc', 'http://localhost', 'http://localhost:5173'],
        },
        hmr: {
            host: 'localhost',
            clientPort: 5173,
        },
        origin: 'http://localhost:5173',
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
});
