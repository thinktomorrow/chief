import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class RadioFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        const radioElements = Array.from(this.element.querySelectorAll('input[type="radio"]'));
        const checkedRadioElement = this.constructor._getCheckedRadioElement(radioElements);

        this.conditionalFields
            .filter((conditionalField) => {
                const isConditionalFieldToBeTriggered = conditionalField.values.find((value) => {
                    if (this.constructor._isValidRegexExpression(value)) {
                        // ...
                    }

                    return value === checkedRadioElement.value;
                });

                return isConditionalFieldToBeTriggered;
            })
            .forEach((conditionalField) => {
                conditionalField.element.classList.remove('hidden');
            });
    }

    static _getCheckedRadioElement(radioElements) {
        return radioElements.find((radioElement) => radioElement.checked);
    }
}

export { RadioFieldTrigger as default };
