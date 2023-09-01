import _debounce from 'lodash/debounce';

const initFormSubmitOnChange = (container = document, selector = '[data-form-submit-on-change]') => {
    const forms = Array.from(container.querySelectorAll(selector));

    forms.forEach((form) => {
        // Trigger form submit if value of a 'normal' input element changes
        form.addEventListener('change', (e) => {
            // Multiselect is handled on its own (see handler below)
            if (e.target.classList.contains('multiselect__input')) return;

            form.submit();
        });

        // Trigger form submit if value of custom multiselect field changes
        window.Eventbus.$on(
            'updated-select',
            _debounce(() => {
                form.submit();
            }, 250)
        );
    });
};

export { initFormSubmitOnChange as default };
