import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/nucleo-icons.css',
                'resources/css/nucleo-svg.css',
                'resources/css/soft-ui-dashboard.css',
                'resources/css/soft-ui-dashboard.min.css',

                'resources/js/app.js',
                'resources/js/customer.js',
                'resources/js/custom.js',
                'resources/js/bootstrap-notify.js',
                'resources/js/bootstrap.js',
                'resources/js/bootstrap.min.js',
                'resources/js/Chart.extension.js',
                'resources/js/chartjs.min.js',
                'resources/js/resources/js/popper.min.js',
                'resources/js/smooth-scrollbar.min.js',
                'resources/js/soft-ui-dashboard.js',
                'resources/js/soft-ui-dashboard.min.js',
                'resources/js/bandwidth-ajax.js',
                
            ],
            refresh: true,
        }),
    ],
});
