import CheckboxFieldTrigger from './CheckboxFieldTrigger.js';

class BooleanFieldTrigger extends CheckboxFieldTrigger {
    _handle() {
        this.currentValues = this._getCurrentValuesFromCheckboxElements();

        if (this.currentValues.length === 0) {
            this.currentValues = ['0'];
        }

        this._toggleConditionalFields();
    }
}

export default BooleanFieldTrigger;
