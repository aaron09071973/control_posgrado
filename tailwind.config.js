import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Azul marino (#1b2847)
                gray: {
                    50: '#f0f5fa',
                    100: '#d6e2f2',
                    200: '#b1c4e6',
                    300: '#8aa3d4',
                    400: '#6885bf',
                    500: '#4e6ba8',
                    600: '#3c548f',
                    700: '#2e4375',
                    800: '#23345c',
                    900: '#1b2847', // Base Navy
                    950: '#0f172a',
                },
                // Naranja (#ef7b00)
                indigo: {
                    50: '#fff5eb',
                    100: '#fee8cd',
                    200: '#fdd4a1',
                    300: '#fcb66d',
                    400: '#fa9d3e',
                    500: '#f5891d',
                    600: '#ef7b00', // Base Orange
                    700: '#cc6600',
                    800: '#a35200',
                    900: '#804000',
                    950: '#4d2600',
                },
                blue: {
                    50: '#fff5eb',
                    100: '#fee8cd',
                    200: '#fdd4a1',
                    300: '#fcb66d',
                    400: '#fa9d3e',
                    500: '#f5891d',
                    600: '#ef7b00',
                    700: '#cc6600',
                    800: '#a35200',
                    900: '#804000',
                    950: '#4d2600',
                },
                emerald: {
                    50: '#fff5eb',
                    100: '#fee8cd',
                    200: '#fdd4a1',
                    300: '#fcb66d',
                    400: '#fa9d3e',
                    500: '#f5891d',
                    600: '#ef7b00',
                    700: '#cc6600',
                    800: '#a35200',
                    900: '#804000',
                    950: '#4d2600',
                },
                amber: {
                    50: '#fff5eb',
                    100: '#fee8cd',
                    200: '#fdd4a1',
                    300: '#fcb66d',
                    400: '#fa9d3e',
                    500: '#f5891d',
                    600: '#ef7b00',
                    700: '#cc6600',
                    800: '#a35200',
                    900: '#804000',
                    950: '#4d2600',
                },
            }
        },
    },

    plugins: [forms],
};
