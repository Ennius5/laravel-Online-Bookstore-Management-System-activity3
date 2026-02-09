import tailwindcss from '@tailwindcss/vite';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
        build: {
        outDir: 'public/build',
        manifest: true,
        rollupOptions: {
            // Explicit input configuration
            input: {
                app: 'resources/css/app.css',
                js: 'resources/js/app.js'
            }
        }
    },
    // Resolve aliases if needed
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
