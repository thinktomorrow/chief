import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class SelectFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        const currentValues = this._getCurrentValuesFromSelectElement();

        this._toggleConditionalFields(currentValues);
    }

    _getCurrentValuesFromSelectElement() {
        const selectElement = this.element.querySelector('select');

        return Array.from(selectElement.querySelectorAll('option'))
            .filter((option) => option.selected)
            .map((option) => option.value);
    }
}

export { SelectFieldTrigger as default };
