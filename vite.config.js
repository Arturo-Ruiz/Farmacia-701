import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",

                "resources/assets/admin/css/app.min.css",
                "resources/assets/admin/css/login.css",

                "resources/assets/admin/js/plugins/perfect-scrollbar.min.js",
                "resources/assets/admin/js/plugins/smooth-scrollbar.min.js",
                "resources/assets/admin/js/plugins/chartjs.min.js",
                "resources/assets/admin/js/plugins/chart.extensions.js",


                "resources/assets/admin/js/auth.js",                
                "resources/assets/admin/js/app.min.js",
                "resources/assets/admin/js/dashboard.js", 
                "resources/assets/admin/js/sidenav.js",

                // Web assets
                "resources/assets/web/css/app.css",
                "resources/assets/web/css/owl.carousel.min.css",
                "resources/assets/web/css/owl.theme.default.min.css",
                
                "resources/assets/web/js/owl.carousel.min.js",
                // "resources/assets/web/js/web.js",

            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
