import { initConditionalFields } from './conditional-fields/init-conditional-fields';
import initCharacterCount from './utilities/character-count';

document.addEventListener('DOMContentLoaded', () => {
    initConditionalFields();
    initCharacterCount();
});

document.addEventListener('form-dialog-opened', (event) => {
    // Next tick my friend... next tick
    setTimeout(() => {
        const dialogEl = event.detail.componentId
            ? document.querySelector(`[wire\\:id="${event.detail.componentId}"]`)
            : document;
        console.log(dialogEl);
        initConditionalFields(dialogEl);
        initCharacterCount();
    }, 0);
});
