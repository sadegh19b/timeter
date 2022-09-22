import { defineConfig } from 'vite';
import { svelte } from '@sveltejs/vite-plugin-svelte';
import laravel from 'laravel-vite-plugin';
import sveltePreprocess from 'svelte-preprocess';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        svelte({
            preprocess: sveltePreprocess({
                postcss: true
            }),
            experimental: {
                prebundleSvelteLibraries: true
            }
        })
    ],
    optimizeDeps: {
        include: ['@inertiajs/inertia']
    },
    resolve: {
        alias: {
            "~": path.resolve(__dirname, './resources'),
            "~js": path.resolve(__dirname, './resources/js'),
            "~css": path.resolve(__dirname, './resources/css'),
            "~page": path.resolve(__dirname, './resources/svelte/pages'),
            "~component": path.resolve(__dirname, './resources/svelte/components'),
            "~store": path.resolve(__dirname, './resources/svelte/stores')
        },
        extensions: ['.mjs', '.js', '.ts', '.jsx', '.tsx', '.json', '.svelte']
    }
});
