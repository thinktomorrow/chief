import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class InputFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        const inputElement = this.element.querySelector('input[type="text"]');

        if (!inputElement) return;

        this._toggleConditionalFields(inputElement.value);
    }
}

export { InputFieldTrigger as default };
