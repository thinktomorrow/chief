import { initConditionalFieldsInContainer, initConditionalFields } from './conditional-fields/init-conditional-fields';

document.addEventListener('DOMContentLoaded', () => {
    initConditionalFields();
});

document.addEventListener('form-dialog-opened', (event) => {
    // Next tick my friend... next tick
    setTimeout(() => {
        const container = event.detail.componentId
            ? document.querySelector(`[wire\\:id="${event.detail.componentId}"]`)
            : document;

        initConditionalFieldsInContainer(container);
    }, 0);
});
