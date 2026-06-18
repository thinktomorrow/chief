import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class RadioFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        this.currentValues = this._getCurrentValuesFromRadioElements();

        this._toggleConditionalFields();
    }

    _getCurrentValuesFromRadioElements() {
        return [...this.element.querySelectorAll('input[type="radio"]')]
            .filter((element) => element.checked)
            .map((element) => element.value);
    }
}

export default RadioFieldTrigger;
