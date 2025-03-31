const characterCount = (container, characterCountEl) => {
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

    // Set count on initial input value
    formField.dispatchEvent(new Event('input'));
};

const initCharacterCount = (selector = '[data-character-count]') => {
    // Scan triggers on initial pageload
    Array.from(document.querySelectorAll(selector)).forEach((el) => {
        characterCount(document, el);
    });
};

export { initCharacterCount as default };
