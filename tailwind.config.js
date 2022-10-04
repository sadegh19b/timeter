const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    content: [
        './resources/**/*.{blade.php,js,svelte,ts}'
    ],
    theme: {
        fontFamily: {
            sans: ['dana', ...defaultTheme.fontFamily.sans],
        },
        extend: {
            // Creating Palette from htmlcsscolor.com
            colors: {
                'brand-primary': {
                    DEFAULT: '#003C71',
                    10: '#E5EBF1',
                    35: '#DDE5EC',
                    50: '#D5DEE7',
                    100: '#BDCCDA',
                    150: '#ACBFD1',
                    200: '#97AFC5',
                    300: '#7D9BB6',
                    400: '#5C82A4',
                    500: '#33638D',
                    600: '#00305A',
                    700: '#002648',
                    800: '#001E3A',
                    900: '#00182E',
                    950: '#001325',
                },
                'brand-secondary': '#007DB3'
            },
            backgroundSize: {
                'size-200': '200% 200%',
            },
            backgroundPosition: {
                'pos-0': '0% 0%',
                'pos-100': '100% 100%',
            },
            minHeight: defaultTheme.spacing
        }
    },
    plugins: [],
    darkMode: 'class'
};
