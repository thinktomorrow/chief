import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class RadioFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        const radioElements = Array.from(this.element.querySelectorAll('input[type="radio"]'));
        const checkedRadioElement = this.constructor._getCurrentValueFromRadioElements(radioElements);

        if (!checkedRadioElement) return;

        this._toggleConditionalFields(checkedRadioElement.value);
    }

    static _getCurrentValueFromRadioElements(elements) {
        return elements.find((element) => element.checked);
    }
}

export { RadioFieldTrigger as default };
