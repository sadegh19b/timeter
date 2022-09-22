module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.svelte",
    ],

    theme: {
        fontFamily: {
            sans: ['dana', ...defaultTheme.fontFamily.sans],
        },
        extend: {},
    },

    plugins: []
};
