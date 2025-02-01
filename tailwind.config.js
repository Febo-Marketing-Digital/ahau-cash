const defaultTheme = require('tailwindcss/defaultTheme');
const { addIconSelectors } = require("@iconify/tailwind");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    daisyui: {
        styled: true,
        themes: ['light', 'dark', 'corporate'],
        base: true,
        styled: true,
        utils: true,
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('daisyui'),
        // Iconify plugin for clean selectors, requires writing a list of icon sets to load
        // Icons usage in HTML:
        //  <span class="iconify mdi-light--home"></span>
        //  <span class="iconify-color vscode-icons--file-type-tailwind"></span>
        addIconSelectors(["lucide"]),
    ],
};
