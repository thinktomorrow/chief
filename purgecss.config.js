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
        'resources/assets/css/components/redactor.scss',
    ],
};
