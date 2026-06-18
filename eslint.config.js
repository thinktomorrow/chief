import js from '@eslint/js';
import globals from 'globals';
import { defineConfig } from 'eslint/config';
import unicorn from 'eslint-plugin-unicorn';
import prettier from 'eslint-config-prettier/flat';

export default defineConfig([
    // The linter only targets our own frontend assets. Exclude vendor and build output.
    {
        ignores: ['**/vendor/', 'public/', 'node_modules/', '**/*.spec.js'],
    },
    {
        files: ['**/*.{js,mjs,cjs}'],
        plugins: { js },
        extends: ['js/recommended', unicorn.configs.recommended, prettier],
        languageOptions: { globals: globals.browser },
        rules: {
            // Our JS modules use the object-literal-with-`this` pattern.
            'unicorn/no-this-outside-of-class': 'off',
            // Too opinionated for this codebase.
            'unicorn/prevent-abbreviations': 'off',
            'unicorn/no-null': 'off',
            'unicorn/prefer-global-this': 'off',
            'unicorn/filename-case': 'off',
        },
    },
]);
