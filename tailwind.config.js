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
            colors: {
                fruna: {
                    yellow: '#FFDE59', // Amarillo corporativo brillante
                    red: '#E10600',    // Rojo Fruna
                    darkred: '#B00000', // Rojo oscuro para gradientes/hovers
                    light: '#FEF08A'   // Amarillo claro para fondos de acento
                }
            },
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
