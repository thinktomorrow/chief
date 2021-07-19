import _isEmpty from 'lodash/isEmpty';
import _debounce from 'lodash/debounce';

class ConditionalFieldTrigger {
    constructor(name, element, formgroupData) {
        this.name = name;
        this.element = element;
        this.conditionalFields = this.constructor._createConditionalFields(formgroupData);

        this.divider = '|';
        this.formgroupToggledByAttribute = 'data-formgroup-toggled-by';

        this._init();
    }

    _init() {
        // Initially hide all conditional fields toggleable by this condition field trigger
        this._hideConditionalFields();

        this.element.addEventListener(
            'input',
            _debounce(() => {
                this._handle();
            }, 250)
        );
    }

    _hideConditionalFields() {
        this.conditionalFields.forEach((conditionalField) => {
            // This attribute is present on the conditional field only if it was already hidden before,
            // or if the attribute was already present on load (e.g active selection has conditional fields)
            if (conditionalField.element.hasAttribute(this.formgroupToggledByAttribute)) return;

            conditionalField.element.classList.add('hidden');
            conditionalField.element.setAttribute(this.formgroupToggledByAttribute, '');
        });
    }

    _showConditionalField(fieldElement) {
        const triggers = fieldElement.getAttribute(this.formgroupToggledByAttribute);

        if (!triggers) {
            fieldElement.setAttribute(this.formgroupToggledByAttribute, this.name);
        } else if (!triggers.split(this.divider).includes(this.name)) {
            fieldElement.setAttribute(this.formgroupToggledByAttribute, triggers + this.divider + this.name);
        }

        fieldElement.classList.remove('hidden');
    }

    _hideConditionalField(fieldElement) {
        let triggers = fieldElement.getAttribute(this.formgroupToggledByAttribute).split(this.divider);

        if (triggers.includes(this.name)) {
            triggers = triggers.filter((trigger) => trigger !== this.name);
            fieldElement.setAttribute(this.formgroupToggledByAttribute, triggers.join(this.divider));
        }

        if (_isEmpty(triggers)) {
            fieldElement.classList.add('hidden');
        }
    }

    static _createConditionalFields(formgroupData) {
        const output = [];

        for (const [key, value] of Object.entries(formgroupData)) {
            const conditionalFieldElement = document.querySelector(`[data-formgroup="${key}"]`);

            if (!conditionalFieldElement) {
                console.error(`Couldn't find formgroup with key ${key}. Make sure this field exists on this model.`);
            } else {
                output.push({
                    element: conditionalFieldElement,
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
