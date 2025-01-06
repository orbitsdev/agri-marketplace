import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import preset from './vendor/filament/support/tailwind.config.preset'
const colors = require('tailwindcss/colors')

/** @type {import('tailwindcss').Config} */
export default {
    presets: [
        require("./vendor/wireui/wireui/tailwind.config.js"),
        preset,


    ],
    content: [

        "./vendor/wireui/wireui/src/*.php",
        "./vendor/wireui/wireui/ts/**/*.ts",
        "./vendor/wireui/wireui/src/WireUi/**/*.php",
        "./vendor/wireui/wireui/src/Components/**/*.php",

        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',

        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                 sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {

                gray: colors.neutral,
                secondary: colors.gray,
                positive: colors.emerald,
                negative: colors.red,
                warning: colors.amber,
                info: colors.blue,

                'eucalyptus': {
                '50': '#ecfdf4',
                '100': '#d1fae3',
                '200': '#a7f3cc',
                '300': '#6de8b1',
                '400': '#33d491',
                '500': '#0fba78',
                '600': '#048e5c',
                '700': '#037951',
                '800': '#065f41',
                '900': '#064e37',
                '950': '#022c20',
                    },

            }
        },
    },

    plugins: [forms, typography],
};
