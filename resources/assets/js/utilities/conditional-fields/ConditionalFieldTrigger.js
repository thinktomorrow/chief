import _isEmpty from 'lodash/isEmpty';
import _debounce from 'lodash/debounce';

/**
 * Conditional field trigger functionality
 * This class is to be extended with specific functionality for the field type
 * @param name field name
 * @param element field element
 * @param conditionalFieldsData conditional field data (field name + trigger values)
 */
class ConditionalFieldTrigger {
    constructor(name, element, conditionalFieldsData) {
        this.name = name;
        this.element = element;
        this.conditionalFields = this.constructor._createConditionalFieldsObject(conditionalFieldsData);

        this.divider = '|';
        this.formgroupToggleAttribute = 'data-conditional-toggled-by';

        this._init();
    }

    _init() {
        // Initially hide all conditional fields toggleable by this condition field trigger
        this._hideConditionalFields();

        this._handle();
        this._watch();
    }

    _watch() {
        this.element.addEventListener(
            'input',
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

    _showConditionalField(element) {
        const triggers = element.getAttribute(this.formgroupToggleAttribute);

        if (!triggers) {
            element.setAttribute(this.formgroupToggleAttribute, this.name);
        } else if (!triggers.split(this.divider).includes(this.name)) {
            element.setAttribute(this.formgroupToggleAttribute, triggers + this.divider + this.name);
        }

        element.classList.remove('hidden');
    }

    _hideConditionalField(element) {
        let triggers = element.getAttribute(this.formgroupToggleAttribute).split(this.divider);

        if (triggers.includes(this.name)) {
            triggers = triggers.filter((trigger) => trigger !== this.name);

            element.setAttribute(this.formgroupToggleAttribute, triggers.join(this.divider));
        }

        if (_isEmpty(triggers)) {
            element.classList.add('hidden');
        }
    }

    _hideConditionalFields() {
        this.conditionalFields.forEach((conditionalField) => {
            // This attribute is present on the conditional field only if it was already hidden before,
            // or if it was shown by another conditional field trigger.
            if (conditionalField.element.hasAttribute(this.formgroupToggleAttribute)) return;

            conditionalField.element.classList.add('hidden');
            conditionalField.element.setAttribute(this.formgroupToggleAttribute, '');
        });
    }

    static _createConditionalFieldsObject(data) {
        const output = [];

        for (const [key, value] of Object.entries(data)) {
            const element = document.querySelector(`[data-conditional="${key}"]`);

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

    static _isValidRegexExpression(input) {
        return input.match(/\/.*\/[dgimsuy]*/);
    }

    static _createRegexFromString(input) {
        const regexParts = input.split('/');

        return new RegExp(regexParts[1], regexParts[2]);
    }
}

export { ConditionalFieldTrigger as default };
