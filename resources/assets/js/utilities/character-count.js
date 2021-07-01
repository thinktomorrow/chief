import EventBus from './EventBus';

function characterCount(panel, characterCountEl) {
    const formFieldId = characterCountEl.getAttribute('data-character-count');
    const formField = panel.querySelector(`#${formFieldId.replaceAll('.', '\\.')}`);
    const max = characterCountEl.getAttribute('data-character-count-max');

    if (!formField) {
        console.error(`Character count not initiated: No formField found by selector:#${formFieldId}`);
        return;
    }

    formField.addEventListener('input', function () {
        const currentLength = this.value.length;

        characterCountEl.classList.remove('text-red-400', 'text-orange-400');

        if (currentLength >= max) {
            characterCountEl.classList.add('text-red-400');
        } else if (currentLength >= (max - (max * 0.1))) {
            characterCountEl.classList.add('text-orange-400');
        }

        characterCountEl.innerHTML = currentLength;
    });

    // Default
    characterCountEl.innerHTML = formField.value.length;
}

EventBus.subscribe('sidebarPanelActivated', (data) => {
    //     const characterCountEl = container.querySelector('[data-character-count=' + id + ']');

    data.panel.el.querySelectorAll('[data-character-count]').forEach((el) => {
        console.log(el);
        characterCount(data.panel.el, el);
    });
});
