import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import preset from './vendor/filament/support/tailwind.config.preset'
const colors = require('tailwindcss/colors')


/** @type {import('tailwindcss').Config} */
export default {
    presets: [
        preset,
        require("./vendor/wireui/wireui/tailwind.config.js"),


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

        './vendor/namu/wirechat/resources/views/**/*.blade.php',
'./vendor/namu/wirechat/src/Livewire/**/*.php'
    ],

    theme: {
        extend: {
            fontFamily: {
                 sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {


              // Reapply Tailwind's default colors to avoid being overridden


            //   'primary': {
            //     '50': '#fdf8f1',
            //     '100': '#f8edd8',
            //     '200': '#f2d9b0',
            //     '300': '#e9be7e',
            //     '400': '#e19c4c',
            //     '500': '#d97d2b',
            //     '600': '#c45e21',
            //     '700': '#a3451d',
            //     '800': '#85391e',
            //     '900': '#6e321c',
            //     '950': '#3f190e',
            //             },

            //             green: colors.green, // Default green palette
            //   indigo: colors.indigo, // Default indigo palette
            //   gray: colors.gray, // Default gray palette
            //   secondary: colors.gray,
            //   positive: colors.emerald,
            //   negative: colors.red,
            //   warning: colors.amber,
            //   info: colors.blue,

            //   // Add custom WireUI or Filament-specific colors explicitly
            //   'eucalyptus': {
            //       '50': '#fbf7f4',
            //       '100': '#f5ebe3',
            //       '200': '#ead6c6',
            //       '300': '#ddb99f',
            //       '400': '#cc9671',
            //       '500': '#c07c54',
            //       '600': '#b26546',
            //       '700': '#94503b',
            //       '800': '#794334',
            //       '900': '#653a2f',
            //       '950': '#361c16',
            //   },
              'primary': {
                '50': '#f0fdf4',
                '100': '#dcfce7',
                '200': '#bbf7d0',
                '300': '#86efac',
                '400': '#4ade80',
                '500': '#22c55e', // vibrant green
                '600': '#16a34a',
                '700': '#15803d',
                '800': '#166534',
                '900': '#14532d',
                '950': '#052e16',
                        },

                        green: colors.green, // Default green palette
              indigo: colors.indigo, // Default indigo palette
              gray: colors.gray, // Default gray palette
              secondary: colors.gray,
              positive: colors.emerald,
              negative: colors.red,
              warning: colors.amber,
              info: colors.blue,

              // Add custom WireUI or Filament-specific colors explicitly
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
