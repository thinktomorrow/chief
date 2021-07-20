import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class InputFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        const inputElement = this.element.querySelector('input[type="text"]');

        if (!inputElement) return;

        this.conditionalFields.forEach((conditionalField) => {
            const isConditionalFieldToBeTriggered = conditionalField.values.find((value) => {
                if (this.constructor._isValidRegexExpression(value)) {
                    return inputElement.value.match(this.constructor._createRegexFromString(value));
                }

                return value === inputElement.value;
            });

            if (isConditionalFieldToBeTriggered) {
                this._showConditionalField(conditionalField.element);
            } else {
                this._hideConditionalField(conditionalField.element);
            }
        });
    }
}

export { InputFieldTrigger as default };
