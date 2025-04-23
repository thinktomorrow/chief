import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class RadioFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        this.currentValues = this._getCurrentValuesFromRadioElements();

        this._toggleConditionalFields();
    }

    _getCurrentValuesFromRadioElements() {
        return Array.from(this.element.querySelectorAll('input[type="radio"]'))
            .filter((element) => element.checked)
            .map((element) => element.value);
    }
}

export { RadioFieldTrigger as default };
