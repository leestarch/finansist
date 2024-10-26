import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import {quasar} from '@quasar/vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        quasar({
            sassVariables: 'resources/js/quasar-variables.sass'
        })
    ],
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost'
        }
    },
});
