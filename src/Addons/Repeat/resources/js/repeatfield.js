import vueFields from '../../../../../resources/assets/js/fields/vue-fields';

export default class {
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

        // TODO: clear values...

        this.fieldsContainer.appendChild(fieldSet);

        fieldSet.innerHTML = this._increaseRepeatIndex(fieldSet);

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

    _increaseRepeatIndex(fieldSet) {
        const firstField = fieldSet.querySelector(this._attributeKey('data-repeat-field'));
        const repeatKey = firstField.getAttribute('data-repeat-field-key');

        // Search pattern - Remove last part of key (e.g. options.0.value => options.0)
        const regexLastPart = /\.([^.]*)$/;
        const originalDottedKey = repeatKey.replace(regexLastPart, '');

        // Replace pattern - Increase last number of key (e.g. options.0 => options.1)
        const regexLastNumber = /([^.]*)$/;
        const newDottedKey = originalDottedKey.replace(regexLastNumber, (match) => parseInt(match, 10) + 1);

        // Replace dotted keys like options.0.value
        const replacedHtml = fieldSet.innerHTML.replace(
            new RegExp(this._escapeRegExp(originalDottedKey), 'g'),
            newDottedKey
        );

        // Replace square brackets keys like options[0][value]
        return replacedHtml.replace(
            new RegExp(this._escapeRegExp(this._replaceDotsWithSquareBrackets(originalDottedKey)), 'g'),
            this._replaceDotsWithSquareBrackets(newDottedKey)
        );
    }

    _escapeRegExp(stringToGoIntoTheRegex) {
        return stringToGoIntoTheRegex.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
    }

    // E.g. foobar.0.test => foobar[0][test]
    _replaceDotsWithSquareBrackets(string) {
        return string.replace(/\.(.+?)(?=\.|$)/g, (match, value) => `[${value}]`);
    }

    // Specific attribute selectors for this repeatField. This allows for nested functionality
    _attributeKey(attributeKey) {
        return `[${attributeKey}="${this.key}"]`;
    }
}
