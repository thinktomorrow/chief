import vueFields from '../../../../../resources/assets/js/fields/vue-fields';
import increaseDeepestIndex from './utils';

class RepeatField {
    constructor(key, container = document) {
        this.key = key;
        this.container = container.querySelector(this._attributeKey('data-repeat-container'));
        this.fieldsContainer = this.container.querySelector(this._attributeKey('data-repeat-fields'));
        this.addTrigger = this.container.querySelector(this._attributeKey('data-repeat-add'));

        /**
         * Register unique trigger handlers
         *
         * if we'd call the method directly as callback, it cannot be
         * removed as it is regarded to be a different function.
         */
        this.addFieldSetReference = (event) => this._addFieldSet(event);
        this.deleteFieldSetReference = (event) => this._deleteFieldSet(event);

        // TODO: this could be set via a field::max() or something
        this.maxFieldSets = 50;

        this._checkMax();
        this._registerEventListeners();
    }

    _registerEventListeners() {
        this.addTrigger.removeEventListener('click', this.addFieldSetReference);
        this.addTrigger.addEventListener('click', this.addFieldSetReference);

        this.fieldsContainer.querySelectorAll(this._attributeKey('data-repeat-delete')).forEach((trigger) => {
            trigger.removeEventListener('click', this.deleteFieldSetReference);
            trigger.addEventListener('click', this.deleteFieldSetReference);
        });
    }

    _checkMax() {
        const underMax = this._amountOfFieldSets() < this.maxFieldSets;

        if (underMax) {
            this.addTrigger.classList.remove('hidden');
        } else {
            this.addTrigger.classList.add('hidden');
        }

        return underMax;
    }

    /** Current count of fieldsets */
    _amountOfFieldSets() {
        return this.fieldsContainer.querySelectorAll(this._attributeKey('data-repeat-fieldset')).length;
    }

    _deleteFieldSet(event) {
        const fieldSet = event.target.closest(this._attributeKey('data-repeat-fieldset'));

        this.fieldsContainer.removeChild(fieldSet);

        this._checkMax();
    }

    _addFieldSet() {
        if (!this._checkMax()) return;

        const fieldSet = this._cloneFieldSet(
            this.fieldsContainer.querySelector(`${this._attributeKey('data-repeat-fieldset')}:last-child`)
        );

        this.fieldsContainer.appendChild(fieldSet);

        fieldSet.innerHTML = this._increaseRepeatIndex(fieldSet);
        this._makeNestedRepeatElsUnique(fieldSet);

        // Clear existing values
        fieldSet.querySelectorAll('[name]').forEach((el) => {
            el.value = null;
        });

        vueFields(fieldSet);

        this._registerEventListeners();
        this._checkMax();

        // Allow for nested repeat
        initRepeatFields(fieldSet);
    }

    _cloneFieldSet(fieldSet) {
        const copiedFieldSet = fieldSet.cloneNode(true);
        const nextIndex = this.fieldsContainer.childElementCount;
        const fieldSetId = copiedFieldSet.id + nextIndex;

        copiedFieldSet.id = fieldSetId;

        return copiedFieldSet;
    }

    _increaseRepeatIndex(fieldSet) {
        const firstField = fieldSet.querySelector(this._attributeKey('data-repeat-field'));
        const repeatKey = firstField.getAttribute('data-repeat-field-key');

        return increaseDeepestIndex(fieldSet.innerHTML, repeatKey);
    }

    static _makeNestedRepeatElsUnique(fieldSet) {
        fieldSet.querySelectorAll('[data-repeat-container]').forEach((el) => {
            const existingRepeatId = el.getAttribute('data-repeat-container');
            el.outerHTML = el.outerHTML.replace(new RegExp(existingRepeatId, 'g'), existingRepeatId + fieldSet.id);
        });
    }

    // Specific attribute selectors for this repeatField. This allows for nested functionality
    _attributeKey(attributeKey) {
        return `[${attributeKey}="${this.key}"]`;
    }
}

function initRepeatFields(container) {
    const repeatContainerAttribute = 'data-repeat-container';
    const repeatContainers = Array.from(container.querySelectorAll(`[${repeatContainerAttribute}]`));

    repeatContainers.forEach((repeatContainer) => {
        const repeatContainerId = repeatContainer.getAttribute(repeatContainerAttribute);
        new RepeatField(repeatContainerId);
    });
}

export { RepeatField, initRepeatFields };
