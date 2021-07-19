import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class RadioFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        const radioElements = Array.from(this.element.querySelectorAll('input[type="radio"]'));
        const checkedRadioElement = this.constructor._getCheckedRadioElement(radioElements);

        // If no radio element was selected on load, return
        if (!checkedRadioElement) return;

        this.conditionalFields.forEach((conditionalField) => {
            const isConditionalFieldToBeTriggered = conditionalField.values.find((value) => {
                if (this.constructor._isValidRegexExpression(value)) {
                    return checkedRadioElement.value.match(this.constructor._createRegexFromString(value));
                }

                return value === checkedRadioElement.value;
            });

            if (isConditionalFieldToBeTriggered) {
                this._showConditionalField(conditionalField.element);
            } else {
                this._hideConditionalField(conditionalField.element);
            }
        });
    }

    static _getCheckedRadioElement(radioElements) {
        return radioElements.find((radioElement) => radioElement.checked);
    }
}

export { RadioFieldTrigger as default };
