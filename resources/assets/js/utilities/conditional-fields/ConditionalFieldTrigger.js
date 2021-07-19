import _debounce from 'lodash/debounce';

class ConditionalFieldTrigger {
    constructor(element, formgroupData) {
        this.element = element;
        this.conditionalFields = this.constructor._createConditionalFields(formgroupData);

        this._init();
    }

    _init() {
        this._hideConditionalFields();

        this.element.addEventListener(
            'input',
            _debounce(() => {
                this._hideConditionalFields();

                this._handle();
            }, 250)
        );
    }

    _hideConditionalFields() {
        this.conditionalFields.forEach((conditionalField) => {
            conditionalField.element.classList.add('hidden');
        });
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
        let isValid = true;

        try {
            new RegExp(input);
        } catch (e) {
            isValid = false;
        }

        return isValid;
    }
}

export { ConditionalFieldTrigger as default };
