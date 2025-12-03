import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

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
                primary: {
                    DEFAULT: '#2E529F',
                    50: '#F3F6FB',
                    100: '#E6EEF8',
                    200: '#B8D0F1',
                    500: '#2E529F'
                },
                accent: {
                    DEFAULT: '#764ba2'
                }
            },
            container: {
                center: true,
                padding: {
                    DEFAULT: '1rem',
                    sm: '1.5rem',
                    lg: '2rem',
                },
            },
            borderRadius: {
                xl: '1rem'
            },
            boxShadow: {
                'soft-lg': '0 10px 30px rgba(16,24,40,0.08)'
            }
        },
    },

    plugins: [forms, typography],
};
