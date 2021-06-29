function submitFormOnEvent(formSelector, container = document, event = 'change') {
    const form = container.querySelector(formSelector);

    form.addEventListener(event, () => {
        // form.submit();
        form.dispatchEvent(new Event('submit'));
    });

    // Also listen for Vue Multiselect change
    window.Eventbus.$on('updated-select', () => {
        // form.submit();
        form.dispatchEvent(new Event('submit'));
    });
}

const submitFormOnChange = function (formSelector, container) {
    submitFormOnEvent(formSelector, container, 'change');
};

const submitFormOnInput = function (formSelector, container) {
    submitFormOnEvent(formSelector, container, 'input');
};

export { submitFormOnChange, submitFormOnInput };
