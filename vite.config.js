import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
    },
    build: {
        outDir: 'public/build', // 👈 Carpeta donde Laravel busca los assets
        manifest: true,         // 👈 Genera el archivo manifest.json
        emptyOutDir: true,      // Limpia antes de cada build
    },
});