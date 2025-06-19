import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'media',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    heme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: { // As designed in HTML preview
                primary: {
                    light: '#8b5cf6',
                    DEFAULT: '#7c3aed',
                    dark: '#6d28d9',
                },
                accent: {
                    DEFAULT: '#ec4899',
                },
                gray: { // Custom gray palette for more control
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                    950: '#020617',
                  }
            },
        },
    },
    plugins: [forms,require('@tailwindcss/typography')],
    //  plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
