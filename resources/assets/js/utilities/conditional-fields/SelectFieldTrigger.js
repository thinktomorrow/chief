import _debounce from 'lodash/debounce';
import _isEmpty from 'lodash/isEmpty';

import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class SelectFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        const currentValues = this.constructor.getCurrentValuesFromSelect(this.element.querySelector('select'));

        if (_isEmpty(currentValues)) return;

        currentValues.forEach((value) => {
            this._toggleConditionalFields(value);
        });
    }

    _watch() {
        window.Eventbus.$on(
            'updated-select',
            _debounce(() => {
                this._handle();
            }, 250)
        );
    }

    static getCurrentValuesFromSelect(selectElement) {
        return Array.from(selectElement.querySelectorAll('option')).map((element) => element.value);
    }
}

export { SelectFieldTrigger as default };
