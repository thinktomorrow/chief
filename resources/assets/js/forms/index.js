import { initConditionalFields } from './conditional-fields/init-conditional-fields';
import initCharacterCount from './utilities/character-count';

document.addEventListener('DOMContentLoaded', () => {
    initConditionalFields();
    initCharacterCount();
});

document.addEventListener('form-dialog-opened', () => {
    // Next tick my friend... next tick
    setTimeout(() => {
        initConditionalFields();
        initCharacterCount();
    }, 200);
});
