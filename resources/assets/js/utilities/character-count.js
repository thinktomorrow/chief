import EventBus from './EventBus';

function characterCount(container, characterCountEl) {
    const formFieldId = characterCountEl.getAttribute('data-character-count');
    const formField = container.querySelector(`#${formFieldId.replaceAll('.', '\\.')}`);
    const max = characterCountEl.getAttribute('data-character-count-max');

    if (!formField) {
        console.error(`Character count not initiated: No formField found by selector: #${formFieldId}`);
        return;
    }

    formField.addEventListener('input', function () {
        const currentLength = this.value.length;

        characterCountEl.classList.remove('text-red-400', 'text-orange-400');

        if (currentLength >= max) {
            characterCountEl.classList.add('text-red-400');
        } else if (currentLength >= max - max * 0.1) {
            characterCountEl.classList.add('text-orange-400');
        }

        characterCountEl.innerHTML = currentLength;
    });

    // Default
    formField.dispatchEvent(new Event('input'));
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-character-count]').forEach((el) => {
        characterCount(document, el);
    });
});

EventBus.subscribe('form-refreshed', (data) => {
    data.element.querySelectorAll('[data-character-count]').forEach((el) => {
        characterCount(data.element, el);
    });
});

EventBus.subscribe('sidebarPanelActivated', (data) => {
    data.panel.el.querySelectorAll('[data-character-count]').forEach((el) => {
        characterCount(data.panel.el, el);
    });
});
