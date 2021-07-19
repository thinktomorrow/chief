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
                console.log(`Couldn't find formgroup with key ${key}`);
            } else {
                output.push({
                    element: conditionalFieldElement,
                    values: value,
                });
            }
        }

        return output;
    }
}

export { ConditionalFieldTrigger as default };
