import CheckboxFieldTrigger from './CheckboxFieldTrigger.js';

class BooleanFieldTrigger extends CheckboxFieldTrigger {
    _handle() {
        this.currentValues = this._getCurrentValuesFromCheckboxElements();
        this._toggleConditionalFields();
    }
}

export default BooleanFieldTrigger;
