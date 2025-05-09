import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class CheckboxFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        this.currentValues = this._getCurrentValuesFromCheckboxElements();

        this._toggleConditionalFields();
    }

    _getCurrentValuesFromCheckboxElements() {
        return Array.from(this.element.querySelectorAll('input[type="checkbox"]'))
            .filter((element) => element.checked)
            .map((element) => element.value);
    }
}

export { CheckboxFieldTrigger as default };
