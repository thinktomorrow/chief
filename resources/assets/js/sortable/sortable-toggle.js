const SortableToggle = function (Sortables, options = {}) {
    this.Sortables = Sortables;

    this.isSorting = options.isSorting || false;
    this.onShowSorting = options.onShowSorting || function () {};
    this.onHideSorting = options.onHideSorting || function () {};

    this.sortToggles = [...document.querySelectorAll('[data-sortable-toggle]')];
    this.hiddenWhenSortingEls = [...document.querySelectorAll('[data-sortable-hide-when-sorting]')];
    this.showWhenSortingEls = [...document.querySelectorAll('[data-sortable-show-when-sorting]')];

    this.classWhenSortingEls = [...document.querySelectorAll('[data-sortable-class-when-sorting]')];
    this.classWhenNotSortingEls = [...document.querySelectorAll('[data-sortable-class-when-not-sorting]')];

    for (const toggle of this.sortToggles) {
        toggle.addEventListener('click', this.toggle.bind(this));
    }

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
        e.target.textContent = 'Stop met sorteren';
        this.showSorting();
    } else {
        e.target.textContent = 'Pas volgorde aan';
        this.hideSorting();
    }
};

SortableToggle.prototype.showSorting = function () {
    for (const el of this.hiddenWhenSortingEls) {
        el.style.display = 'none';
    }

    for (const el of this.showWhenSortingEls) {
        el.style.removeProperty('display');
    }

    for (const el of this.classWhenSortingEls) {
        el.classList.add(el.dataset.sortableClassWhenSorting.split(','));
    }

    for (const el of this.classWhenNotSortingEls) {
        el.classList.remove(el.dataset.sortableClassWhenNotSorting.split(','));
    }

    for (const sortableInstance of this.Sortables) {
        sortableInstance.option('disabled', false);
    }

    this.onShowSorting();
};

SortableToggle.prototype.hideSorting = function () {
    for (const el of this.hiddenWhenSortingEls) {
        el.style.removeProperty('display');
    }

    for (const el of this.showWhenSortingEls) {
        el.style.display = 'none';
    }

    for (const el of this.classWhenSortingEls) {
        el.classList.remove(el.dataset.sortableClassWhenSorting.split(','));
    }

    for (const el of this.classWhenNotSortingEls) {
        el.classList.add(el.dataset.sortableClassWhenNotSorting.split(','));
    }

    for (const sortableInstance of this.Sortables) {
        sortableInstance.option('disabled', true);
    }

    this.onHideSorting();
};

export default SortableToggle;
