import EventBus from '../utilities/EventBus';

const Submit = {
    handle(responseData, currentElement, targetElement, tags) {
        // Reset any error
        currentElement.querySelectorAll('[data-error-placeholder]').forEach((errorElement) => {
            errorElement.classList.add('hidden');
        });

        if (responseData.errors) {
            Object.keys(responseData.errors).forEach((name) => {
                const errorElement = currentElement.querySelector(`[data-error-placeholder="${name}"]`);

                if (!errorElement) return;

                errorElement.classList.remove('hidden');
                errorElement.querySelector('[data-error-placeholder-content]').innerHTML = responseData.errors[name];
            });

            return;
        }

        EventBus.publish('chief-form-submitted', {
            currentElement,
            targetElement,
            tags,
            response: responseData,
        });
    },
};

export { Submit as default };
