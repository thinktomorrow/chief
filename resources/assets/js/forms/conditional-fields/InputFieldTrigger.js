import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class InputFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        const inputElement = this.element.querySelector('input[type="text"]');

        this._toggleConditionalFields([inputElement.value]);
    }
}

export { InputFieldTrigger as default };
