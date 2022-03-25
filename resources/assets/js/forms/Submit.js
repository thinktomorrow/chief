import EventBus from '../utilities/EventBus';

const Submit = {
    handle(responseData, currentElement, targetElement, tags, successCallback) {
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

        if (successCallback) {
            // Halt default process if success callback returns false. Success callback can handle redirect its own way.
            if (successCallback() === false) {
                return;
            }
        }

        if (responseData.redirect_to) {
            window.location.href = responseData.redirect_to;
        }
    },
};

export { Submit as default };
