module.exports = {
    mode: 'all',
    preserveHtmlElements: false,

    css: ['resources/assets/sass/main.scss'],

    content: [
        'resources/views/**/*.blade.php',
        'resources/assets/**/*.js',
        'resources/assets/**/*.vue',
        'resources/assets/css/components/slim.scss',
        'resources/assets/css/components/multiselect.scss',
        'node_modules/vue-multiselect/dist/vue-multiselect.min.css',
        'resources/assets/css/components/redactor.scss',
        'src/Addons/Repeat/resources/views/**/*.blade.php',
    ],
};
