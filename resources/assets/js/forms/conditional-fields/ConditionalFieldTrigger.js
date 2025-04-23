// import _isEmpty from 'lodash/isEmpty';
import _debounce from 'lodash/debounce';

/**
 * Conditional field trigger functionality.
 * This class is to be extended with specific functionality for the desired field type.
 * @param name field name
 * @param element field element
 * @param conditionalFieldsData conditional field data (field name + trigger values)
 */
class ConditionalFieldTrigger {
    constructor(name, element, conditionalFieldsData) {
        this.name = name;
        this.element = element;
        this.conditionalFields = this.constructor._createConditionalFieldsObject(conditionalFieldsData);

        this.formgroupToggleAttribute = 'data-conditional-toggled-by';
        this._init();
    }

    /**
     * Initialise the ConditionalFieldTrigger ...
     */
    _init() {
        // Initially hide all conditional fields toggleable by this condition field trigger
        this._hideConditionalFields();

        this._watch();

        // Allow next tick in order for all elements to be rendered.
        setTimeout(() => {
            this._handle();
        }, 100);
    }

    /**
     * Implements how the element should be watched and trigger the _handle callback method.
     */
    _watch() {
        this.element.addEventListener(
            'input',
            _debounce(() => {
                this._handle();
            }, 250)
        );
    }

    /**
     * Checks if the current values are trigger values for this formgroups conditional fields.
     * Based on the result, toggle the conditional fields.
     * If one or more of the current values matches the conditional fields values, show it.
     * Otherwise, hide the conditional field.
     * @param {Array<String>} currentValues
     */
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

    /**
     * Show the conditional field to the user.
     * Also share the triggers name by adding it to the formgroup toggle data attribute.
     * @param {Object} element
     */
    _showConditionalField(element) {
        const fieldState = window.conditionalFieldsToggledByState.find((item) => item.field === element);

        if (fieldState) {
            fieldState.toggledBy = [...new Set([...fieldState.toggledBy, this.name])];
        } else {
            window.conditionalFieldsToggledByState.push({
                field: element,
                toggledBy: [this.name],
            });
        }

        element.classList.remove('hidden');
    }

    /**
     * Remove the triggers name from the formgroup toggle attribute if present.
     * If after that the attribute is empty (meaning there are no more active triggers), the element will be hidden.
     * @param {Object} element
     */
    _hideConditionalField(element) {
        const fieldState = window.conditionalFieldsToggledByState.find((item) => item.field === element);

        if (!fieldState) {
            element.classList.add('hidden');
            return;
        }

        fieldState.toggledBy = fieldState.toggledBy.filter((trigger) => trigger !== this.name);

        if (fieldState.toggledBy.length === 0) {
            window.conditionalFieldsToggledByState = window.conditionalFieldsToggledByState.filter(
                (item) => item.field === element
            );

            element.classList.add('hidden');
        }
    }

    /**
     * Hide all conditional fields, if the formgroup toggle attribute is not present.
     */
    _hideConditionalFields() {
        this.conditionalFields.forEach((conditionalField) => {
            conditionalField.element.classList.add('hidden');
        });
    }

    /**
     * Builds an Array of conditional field Objects with element and trigger values.
     * @param {Object} data
     * @returns {Array<Object>}
     */
    static _createConditionalFieldsObject(data) {
        const output = [];

        for (const [key, value] of Object.entries(data)) {
            const element = document.querySelector(`[data-field-key="${key}"]`);

            if (!element) {
                console.error(
                    /* eslint-disable-next-line */
                    `Error while trying to create conditional fields: Couldn't find formgroup with key ${key}. Make sure this field exists on this model.`
                );
            } else {
                output.push({
                    element,
                    values: value,
                });
            }
        }

        return output;
    }

    /**
     * Check if the given string can be interpreted as a regular expression.
     * @param {String} input
     * @returns {Boolean}
     */
    static _isValidRegexExpression(input) {
        return input.match(/\/.*\/[dgimsuy]*/);
    }

    /**
     * Creates a regular expression from a given string.
     * @param {String} input
     * @returns {RegExp}
     */
    static _createRegexFromString(input) {
        const regexParts = input.split('/');

        return new RegExp(regexParts[1], regexParts[2]);
    }
}

export { ConditionalFieldTrigger as default };
