const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'logo-blue': '#1B3E86'
            },
            listStyleType: {
                circle: 'circle'
            },
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
            textColor: ['visited']
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/aspect-ratio')],
};
