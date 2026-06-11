import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Syne', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    DEFAULT: '#0F1F3D',
                    light: '#1A3260',
                },
                emerald: {
                    DEFAULT: '#00C896',
                    light: '#E6FAF5',
                },
                coral: {
                    DEFAULT: '#FF6B35',
                    light: '#FFF0EB',
                },
                slate: {
                    DEFAULT: '#F7F9FC',
                    dark: '#E8EDF5',
                },
            },
        },
    },

    plugins: [forms],
};
