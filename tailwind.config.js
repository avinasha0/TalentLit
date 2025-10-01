import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import aspectRatio from '@tailwindcss/aspect-ratio';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/**/*.vue',
        './app/View/Components/**/*.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
    ],
    safelist: [
        'bg-primary-50',
        'bg-primary-100',
        'bg-primary-600',
        'bg-primary-700',
        'text-primary-100',
        'from-primary-400',
        'from-primary-600',
        'to-primary-600',
        'to-primary-700',
        'focus:ring-primary-500',
        'focus:border-primary-500',
        'hover:bg-primary-700',
        // Secondary color classes
        'bg-secondary-50',
        'bg-secondary-100',
        'bg-secondary-600',
        'bg-secondary-700',
        'text-secondary-100',
        'from-secondary-400',
        'from-secondary-600',
        'to-secondary-600',
        'to-secondary-700',
        'focus:ring-secondary-500',
        'focus:border-secondary-500',
        'hover:bg-secondary-700',
        // Status/badge classes for dynamic content
        'bg-green-100', 'bg-green-200', 'text-green-600',
        'bg-yellow-100', 'text-yellow-600',
        'bg-red-100', 'text-red-600',
        'bg-blue-100', 'text-blue-600',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#f3f0ff',
                    100: '#e9e5ff',
                    200: '#d6ceff',
                    300: '#b8a6ff',
                    400: '#9375ff',
                    500: '#6E46AE', // Royal Purple
                    600: '#5d3a8f',
                    700: '#4d2e75',
                    800: '#40255f',
                    900: '#35204e',
                },
                secondary: {
                    50: '#f0fdfc',
                    100: '#ccfbf1',
                    200: '#99f6e4',
                    300: '#5eead4',
                    400: '#2dd4bf',
                    500: '#00B6B4', // Tiffany Blue
                    600: '#00a39e',
                    700: '#008a87',
                    800: '#006d6b',
                    900: '#005a58',
                },
            },
        },
    },

    plugins: [forms, typography, aspectRatio],
};
