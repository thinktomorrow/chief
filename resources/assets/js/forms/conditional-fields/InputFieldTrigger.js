import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class InputFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        const inputElement = this.element.querySelector('input[type="text"]');

        this.currentValues = [inputElement.value];

        this._toggleConditionalFields();
    }
}

export { InputFieldTrigger as default };
