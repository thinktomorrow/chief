import _debounce from 'lodash/debounce';

import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class SelectFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        const currentValues = this._getCurrentValuesFromSelectElement();

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

    _getCurrentValuesFromSelectElement() {
        const selectElement = this.element.querySelector('select');

        return Array.from(selectElement.querySelectorAll('option')).map((element) => element.value);
    }
}

export { SelectFieldTrigger as default };
