import { vueFields } from '../../../../../resources/assets/js/fields/vue-fields';

export default class {
    constructor(key, container = document) {
        console.log('soo good', key);
        this.key = key;
        this.container = container.querySelector(this._attributeKey('data-repeat-container'));
        this.fieldsContainer = this.container.querySelector(this._attributeKey('data-repeat-fields'));
        this.addTrigger = this.container.querySelector(this._attributeKey('data-repeat-add'));

        // TODO: this could be set via a field::max() or something
        this.maxFieldSets = 50;

        this._checkMax();
        this._registerEventListeners();
    }

    _registerEventListeners() {
        // this.addTrigger.removeEventListener('click', this._addFieldSet);
        this.addTrigger.addEventListener('click', this._addFieldSet);

        this.fieldsContainer.querySelectorAll(this._attributeKey('data-repeat-delete')).forEach((trigger) => {
            // trigger.removeEventListener('click', this._deleteFieldSet);
            trigger.addEventListener('click', this._deleteFieldSet);
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
        console.log('deleting...');
        const fieldSet = event.target.closest(this._attributeKey('data-repeat-fieldset'));

        this.fieldsContainer.removeChild(fieldSet);

        this._checkMax();
    }

    _addFieldSet() {
        console.log('adding...');
        if (!this._checkMax()) return;

        const fieldSet = this._cloneFieldSet(this.fieldsContainer.querySelector());

        fieldSet.innerHTML = fieldSet.innerHTML.replace(/\]\[0\]\[/g, `][${this._amountOfFieldSets() + 1}][`);

        this.fieldsContainer.appendChild(fieldSet);

        vueFields(fieldSet);

        // TODO: trigger redactor...
        // $R('[data-editor]');

        this._registerEventListeners();
        this._checkMax();
    }

    _cloneFieldSet(fieldSet) {
        const copiedFieldSet = fieldSet.cloneNode(true);
        const nextIndex = this.fieldsContainer.childElementCount;
        const fieldSetId = copiedFieldSet.id + nextIndex;

        copiedFieldSet.id = fieldSetId;

        return copiedFieldSet;
    }

    // Specific attribute selectors for this repeatField. This allows for nested functionality
    _attributeKey(attributeKey) {
        return `[${attributeKey}="${this.key}"]`;
    }
}
