import Sortable from 'sortablejs';
import EventBus from '../utilities/EventBus';
import SortableToggle from './sortable-toggle';

/**
 * Add data-sortable to a container. Each direct child is now sortable.
 *
 * The following data attributes can be used for tweaking the behavior:
 * - data-sortable-is-sorting               show sorting handles on pageload
 *
 * Visibility options on page elements:
 * - data-sortable-class-when-sorting       set these classes on the element when sorting. e.g. "btn, btn-primary"
 * - data-sortable-class-when-not-sorting   classes on the element when not sorting
 * - data-sortable-hide-when-sorting        to hide the element when sorting
 * - data-sortable-show-when-sorting        to show the element when sorting
 *
 * @param options
 * @constructor
 */

const SortableGroup = function (options) {
    this.Sortables = [];
    this.sortableGroupEl = options.sortableGroupEl || document.getElementById('js-sortable');
    this.sortableGroupId = options.sortableGroupId || 'models';
    this.sortableIdAttribute = options.sortableId || 'data-sortable-id';
    this.sortableIdType = options.sortableIdType || 'int'; // int, string
    this.endpoint = options.endpoint;
    this.nestedEndpoint = options.nestedEndpoint;

    if (!this.endpoint) {
        throw new Error('Missing endpoint for sortable js. Please set the options.endpoint value');
    }

    // Optional draggable handle instead of entire element
    this.handle = options.handle || null;

    this._init();

    new SortableToggle(this.Sortables, { isSorting: options.isSorting || false });
};

SortableGroup.prototype.destroy = function () {
    this.Sortables.forEach((sortable) => sortable.destroy());
};

SortableGroup.prototype._init = function () {
    const self = this;

    this.Sortables.push(
        Sortable.create(this.sortableGroupEl, {
            group: this.sortableGroupId,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            invertSwap: false,
            dataIdAttr: this.sortableIdAttribute,
            handle: this.handle,
            animation: 150,
            easing: 'cubic-bezier(0.87, 0, 0.13, 1)',
            filter: '[data-sortable-ignore]',

            // On the onEnd event, we take care of parent changes.
            onEnd: (evt) => {
                console.log(self.nestedEndpoint);

                if (!self.nestedEndpoint) return;

                // if the parent is the same DOM element - we abort here.
                if (evt.to === evt.from) return;

                const itemId = evt.item.dataset.sortableId;
                const parentId = evt.to.dataset.sortableGroupId || null;

                fetch(self.nestedEndpoint, {
                    method: 'post',
                    body: JSON.stringify({
                        itemId,
                        parentId,
                        order: evt.newIndex,
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })
                    .then((response) => response.json())
                    .then(() => {
                        window.Eventbus.$emit('create-notification', 'success', 'Item is verplaatst.️', 1000);
                    })
                    .catch((error) => {
                        window.Eventbus.$emit(
                            'create-notification',
                            'error',
                            'Het item kan niet worden verplaatst. Er is iets misgelopen.️'
                        );

                        console.error(error);
                    });
            },

            // Sort new order
            store: {
                set(sortable) {
                    const indices = self._filterSortableIndices(sortable.toArray());

                    if (indices.length < 1) return;

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

SortableGroup.prototype._filterSortableIndices = function (indices) {
    // Sortablejs will generate '4w1' for elements without data id
    // This is used for instance on the plus icons in the fragments,
    // which are elements which should not impact the order numbers.
    // ex: '4w1', '5tj', '6f1', '6iq'

    if (this.sortableIdType === 'int') {
        return indices.filter((index) => index.match(/^[0-9]+$/));
    }

    return indices;
};

export { SortableGroup as default };
