import { defineConfig, loadEnv } from 'vite';

import { fileURLToPath } from 'node:url';
import laravel from 'laravel-vite-plugin';
import path from 'node:path';
import vuePlugin from '@vitejs/plugin-vue';

const Vue = fileURLToPath(
	new URL(
		'vue',
		import.meta.url
	)
);

export default ({ mode }) => {
    process.env = {...process.env, ...loadEnv(mode, process.cwd())};

    return defineConfig({
        base: '/modules/nsgastro/build/',
        plugins: [
            vuePlugin(),
            laravel({
                input: [
                    'Resources/ts/Gastro.ts',
                    'Resources/ts/GastroKitchen.ts',
                ],
                refresh: [ 
                    'Resources/**', 
                ]
            })
        ],
        resolve: {
            alias: {
                '@': path.resolve(__dirname, 'Resources/ts'),
            }
        },
        build: {
            outDir: 'Public/build',
            manifest: true,
            rollupOptions: {
                input: [
                    './Resources/ts/Gastro.ts',
                    './Resources/ts/GastroKitchen.ts',
                ],
                output: {
                    manualChunks(id) {
                        if ( id.includes( 'Resources/ts/scss/gastro.scss' ) ) {
                            return 'gastro-assets';
                        }
                    }
                }
            }
        }        
    });
}