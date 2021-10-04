const SortableToggle = function (Sortables, options = {}) {
    this.Sortables = Sortables;

    // Toggle
    this.isSorting = options.isSorting || false;
    this.sortToggles = Array.from(document.querySelectorAll('[data-sortable-toggle]'));
    this.hiddenWhenSortingEls = Array.from(document.querySelectorAll('[data-sortable-hide-when-sorting]'));
    this.showWhenSortingEls = Array.from(document.querySelectorAll('[data-sortable-show-when-sorting]'));

    this.sortToggles.forEach((toggle) => {
        toggle.addEventListener('click', this.toggle.bind(this));
    });

    // Default view
    if (this.isSorting) {
        this.showSorting();
    } else {
        this.hideSorting();
    }
};

SortableToggle.prototype.toggle = function (e) {
    this.isSorting = !this.isSorting;

    if (this.isSorting) {
        e.target.innerText = 'Stop met sorteren';
        this.showSorting();
    } else {
        e.target.innerText = 'Sorteer handmatig';
        this.hideSorting();
    }
};

SortableToggle.prototype.showSorting = function () {
    this.hiddenWhenSortingEls.forEach((el) => {
        el.classList.add('hidden');
    });

    this.showWhenSortingEls.forEach((el) => {
        el.classList.remove('hidden');
    });

    this.Sortables.forEach((sortableInstance) => {
        sortableInstance.option('disabled', false);
    });
};

SortableToggle.prototype.hideSorting = function () {
    this.hiddenWhenSortingEls.forEach((el) => {
        el.classList.remove('hidden');
    });

    this.showWhenSortingEls.forEach((el) => {
        el.classList.add('hidden');
    });

    this.Sortables.forEach((sortableInstance) => {
        sortableInstance.option('disabled', true);
    });
};

export { SortableToggle as default };
