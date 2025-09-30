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
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                },
            },
        },
    },

    plugins: [forms, typography, aspectRatio],
};
