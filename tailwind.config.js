import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#7C3AED',
                    hover: '#6D28D9',
                    light: '#8B5CF6',
                },
                dark: {
                    900: '#0f1117',
                    800: '#1a1d2e',
                    700: '#252840',
                    600: '#2d3154',
                },
                sidebar: '#13152b',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [forms],
};