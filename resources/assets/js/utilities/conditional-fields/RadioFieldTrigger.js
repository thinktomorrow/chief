import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class RadioFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        const currentValue = this._getCurrentValueFromRadioElements();

        this._toggleConditionalFields(currentValue);
    }

    _getCurrentValueFromRadioElements() {
        return Array.from(this.element.querySelectorAll('input[type="radio"]')).find((element) => element.checked);
    }
}

export { RadioFieldTrigger as default };
