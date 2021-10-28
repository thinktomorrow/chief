import Sortable from 'sortablejs';
import EventBus from './EventBus';
import SortableToggle from './sortable-toggle';

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

    this._init();

    new SortableToggle(this.Sortables, { isSorting: options.isSorting || false });
};

IndexSorting.prototype.destroy = function () {
    this.Sortables.forEach((sortable) => sortable.destroy());
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
                    const indices = self._filterSortableIndices(sortable.toArray());

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

                            EventBus.publish('sortableStored');
                        })
                        .catch((error) => {
                            window.Eventbus.$emit(
                                'create-notification',
                                'error',
                                'Sortering kan niet worden bewaard. Er is iets misgelopen.️'
                            );

                            EventBus.publish('sortableStored');

                            console.error(error);
                        });
                },
            },
        })
    );
};

IndexSorting.prototype._filterSortableIndices = function (indices) {
    // Sortablejs will generate '4w1' for elements without data id
    // This is used for instance on the plus icons in the fragments,
    // which are elements which should not impact the order numbers.
    // ex: '4w1', '5tj', '6f1', '6iq'
    return indices.filter((index) => index.match(/^[0-9]+$/));
};

export { IndexSorting as default };
