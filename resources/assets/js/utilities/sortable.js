import Sortable from 'sortablejs';
import EventBus from './EventBus';

const IndexSorting = function (options) {
    this.Sortables = [];
    this.sortableGroupEl = options.sortableGroupEl || document.getElementById('js-sortable');
    this.sortableIdAttribute = options.sortableId || 'data-sortable-id';
    this.endpoint = options.endpoint;

    if (!this.endpoint) {
        throw new Error('Missing endpoint for sortable js. Please set the options.endpoint value');
    }

    // Optional draggable handle instead of entire element
    this.handle = options.handle || null;

    // Toggle
    this.isSorting = options.isSorting || false;
    this.sortToggles = Array.from(document.querySelectorAll('[data-sortable-toggle]'));
    this.hiddenWhenSortingEls = Array.from(document.querySelectorAll('[data-sortable-hide-when-sorting]'));
    this.showWhenSortingEls = Array.from(document.querySelectorAll('[data-sortable-show-when-sorting]'));

    this._init();
};

IndexSorting.prototype.destroy = function () {
    this.Sortables.forEach((sortable) => sortable.destroy());
};

IndexSorting.prototype.toggle = function (e) {
    this.isSorting = !this.isSorting;

    if (this.isSorting) {
        e.target.innerText = 'Stop met sorteren';
        this.showSorting();
    } else {
        e.target.innerText = 'Sorteer handmatig';
        this.hideSorting();
    }
};

IndexSorting.prototype.showSorting = function () {
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

IndexSorting.prototype.hideSorting = function () {
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

IndexSorting.prototype._init = function () {
    const self = this;

    this.Sortables.push(
        Sortable.create(this.sortableGroupEl, {
            group: 'models',
            fallbackOnBody: true,
            swapThreshold: 0.65,
            dataIdAttr: this.sortableIdAttribute,
            handle: this.handle,
            animation: 200,
            easing: 'cubic-bezier(0.87, 0, 0.13, 1)',
            filter: '[data-sortable-ignore]',

            store: {
                set(sortable) {
                    const indices = sortable.toArray();
                    // let indices = self._filterSortableIndices(sortable.toArray());

                    fetch(self.endpoint, {
                        method: 'post',
                        body: JSON.stringify({
                            indices,
                        }),
                        headers: {
                            'Content-Type': 'application/json',
                        },
                    })
                        .then((response) => response.json())
                        .then(() => {
                            window.Eventbus.$emit('create-notification', 'success', 'Nieuwe sortering bewaard.️', 2000);

                            EventBus.publish('sortable-stored');
                        })
                        .catch((error) => {
                            window.Eventbus.$emit(
                                'create-notification',
                                'error',
                                'Sortering kan niet worden bewaard. Er is iets misgelopen.️'
                            );

                            EventBus.publish('sortable-stored');

                            console.error(error);
                        });
                },
            },
        })
    );

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

// IndexSorting.prototype._filterSortableIndices = function (indices) {
//     // Sortablejs will generate '4w1' for elements without data id
//     // This is used for instance on the plus icons in the fragments,
//     // which are elements which should not impact the order numbers.
//     return indices.filter((index) => index !== 'remove-before-post');
// };

export { IndexSorting as default };
