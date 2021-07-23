import _debounce from 'lodash/debounce';

import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class SelectFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        const currentValues = this.constructor.getCurrentValuesFromSelect(this.element.querySelector('select'));

        this._toggleConditionalFields(currentValues);
    }

    _watch() {
        window.Eventbus.$on(
            'updated-select',
            _debounce(() => {
                this._handle();
            }, 250)
        );
    }

    _toggleConditionalFields(currentValues) {
        this.conditionalFields.forEach((conditionalField) => {
            const isConditionalFieldToBeTriggered = conditionalField.values.find((conditionalFieldValue) => {
                if (this.constructor._isValidRegexExpression(conditionalFieldValue)) {
                    return currentValues.find((currentValue) => {
                        const regex = this.constructor._createRegexFromString(conditionalFieldValue);

                        return currentValue.match(regex);
                    });
                }

                return currentValues.includes(conditionalFieldValue);
            });

            if (isConditionalFieldToBeTriggered) {
                this._showConditionalField(conditionalField.element);
            } else {
                this._hideConditionalField(conditionalField.element);
            }
        });
    }

    static getCurrentValuesFromSelect(selectElement) {
        return Array.from(selectElement.querySelectorAll('option')).map((element) => element.value);
    }
}

export { SelectFieldTrigger as default };
