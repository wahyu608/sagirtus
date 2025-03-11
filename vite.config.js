import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // server: {
    //     host: true, // Agar bisa diakses dari HP
    //     port: 5173, // Port default Vite
    //     strictPort: true,
    //     hmr: {
    //         host: '192.168.10.65' // Ganti dengan IP laptop kamu
    //     }
    // }
});
