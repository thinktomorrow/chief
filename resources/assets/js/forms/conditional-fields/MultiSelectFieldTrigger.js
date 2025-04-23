import _debounce from 'lodash/debounce';

import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class MultiSelectFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        this.currentValues = this._getCurrentValuesFromSelectElement();

        this._toggleConditionalFields();
    }

    _watch() {
        this.element.addEventListener(
            'change',
            _debounce(() => {
                this._handle();
            }, 250)
        );
    }

    _getCurrentValuesFromSelectElement() {
        const selectElement = this.element.querySelector('select');

        return Array.from(selectElement.querySelectorAll('option'))
            .filter((option) => option.selected)
            .map((option) => option.value);
    }
}

export { MultiSelectFieldTrigger as default };
