const SortableToggle = function (Sortables, options = {}) {
    this.Sortables = Sortables;

    this.isSorting = options.isSorting || false;
    this.onShowSorting = options.onShowSorting || function () {};
    this.onHideSorting = options.onHideSorting || function () {};

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
        e.target.innerText = 'Pas volgorde aan';
        this.hideSorting();
    }
};

SortableToggle.prototype.showSorting = function () {
    this.hiddenWhenSortingEls.forEach((el) => {
        el.style.display = 'none';
    });

    this.showWhenSortingEls.forEach((el) => {
        el.style.removeProperty('display');
    });

    this.Sortables.forEach((sortableInstance) => {
        sortableInstance.option('disabled', false);
    });

    this.onShowSorting();
};

SortableToggle.prototype.hideSorting = function () {
    this.hiddenWhenSortingEls.forEach((el) => {
        el.style.removeProperty('display');
    });

    this.showWhenSortingEls.forEach((el) => {
        el.style.display = 'none';
    });

    this.Sortables.forEach((sortableInstance) => {
        sortableInstance.option('disabled', true);
    });

    this.onHideSorting();
};

export { SortableToggle as default };
