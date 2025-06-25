const Selectlist = (config) => ({
    // Set the selection either if we are in a livewire form based on the given
    // form property value or else on the passed selection
    selection: config.selection || [],
    options: config.options || [],
    grouped: config.grouped || false,
    showingSelectBox: false,
    searchTerm: '',
    get filteredOptions() {
        if (this.grouped) {
            return this.options.map((group) => {
                const newGroup = { ...group };

                newGroup.choices = group.choices.filter(
                    (option) => !this.rawSelection.some((value) => option.value.toString() === value.toString())
                );
                return newGroup;
            });
        }

        return this.options.filter(
            (option) => !this.rawSelection.some((value) => option.value.toString() === value.toString())
        );
    },
    init() {
        this.$nextTick(() => {
            this.updateSelectOptions();

            if (!this.rawSelection || this.rawSelection.length === 0) {
                this.showSelectBox();
            } else {
                this.hideSelectBox();
            }

            this.hideSelectBoxWhenUnfocused();
            this.preserveSearchTerm();
        });
    },
    // With wire:model, the selection can be null or undefined, so we need to handle that case.
    get rawSelection() {
        if (!this.selection) {
            return [];
        }

        return this.selection;
    },
    get selectedOptions() {
        return this.rawSelection.map((value) => this.findOptionByValue(value));
    },
    findOptionByValue(value) {
        if (this.grouped) {
            for (const group of this.options) {
                const match = group.choices.find((option) => option.value.toString() === value.toString());
                if (match !== undefined) {
                    return match;
                }
            }

            console.error('No option found for value', value);
            return null;
        }

        return this.options.find((option) => option.value.toString() === value.toString());
    },
    addItem() {
        this.selection = [...this.rawSelection, ...this.$el.choices.getValue(true)];
        this.onSelectionChange();
    },
    removeItem(value) {
        const index = this.rawSelection.findIndex((val) => val.toString() === value.toString());

        if (index === -1) return;

        this.rawSelection.splice(index, 1);
        this.onSelectionChange();
    },
    sortSelection(sortedSelection) {
        this.selection = sortedSelection;
        this.onSelectionChange();
    },
    onSelectionChange() {
        // Notify change event for outside listeners, such as the Conditional fields js.
        this.$dispatch('select-list-change');

        // Notify wired model
        this.$dispatch('input', this.rawSelection);

        this.updateSelectOptions();
    },
    updateSelectOptions() {
        this.$el.choices.clearStore();
        this.$el.choices.setChoices(this.filteredOptions);
    },
    get allowSelectBox() {
        return this.filteredOptions.length > 0;
    },
    showSelectBox() {
        this.$el.choices.containerOuter.element.classList.remove('hidden');
        this.$el.choices.input.element.focus();
        this.showingSelectBox = true;
    },
    hideSelectBox() {
        if (this.rawSelection.length > 0) {
            this.$el.choices.containerOuter.element.classList.add('hidden');
            this.showingSelectBox = false;
        }

        this.resetSearchTerm();
    },
    resetSearchTerm() {
        this.searchTerm = '';

        this.$el.choices.clearInput();
    },
    forceSearch(value) {
        this.$el.choices.input.element.value = value;
        this.$el.choices.input.setWidth();
        this.$el.choices._searchChoices(value);
    },
    hideSelectBoxWhenUnfocused() {
        this.$el.choices.input.element.addEventListener('focusout', () => {
            this.hideSelectBox();
        });
    },

    // Choices js resets the search term after adding an item. Here we override this behaviour.
    preserveSearchTerm() {
        this.$el.addEventListener('search', (event) => {
            this.searchTerm = event.detail.value;
        });

        this.$el.addEventListener('addItem', () => {
            setTimeout(() => {
                if (this.searchTerm) this.forceSearch(this.searchTerm);
            }, 0);
        });
    },
});

export { Selectlist as default };
