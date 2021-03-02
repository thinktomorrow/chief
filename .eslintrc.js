module.exports = {
    env: {
        browser: true,
        es2021: true,
        node: true,
    },
    extends: 'eslint:recommended',
    parserOptions: {
        ecmaVersion: 12,
        sourceType: 'module',
    },
    rules: {
        // strict: ['error', 'never'],
        // quotes: ['error', 'single'],
        // indent: ['error', 4],
    },
};
