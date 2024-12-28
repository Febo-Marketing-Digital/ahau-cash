import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const port = 5173;
const origin = `${process.env.DDEV_PRIMARY_URL}:${port}`;
console.log(origin);

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        // respond to all network requests:
        host: "0.0.0.0",
        port: port,
        strictPort: true,
        // Defines the origin of the generated asset URLs during development
        origin: origin,
    },
});
