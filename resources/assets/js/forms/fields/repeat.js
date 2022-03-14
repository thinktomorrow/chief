import Sortable from 'sortablejs';
import EventBus from '../../utilities/EventBus';
import vueFields from './vue-fields';

const Repeat = function (endpoint, containerId, sectionSelector, sectionName) {
    this.endpoint = endpoint;
    this.containerId = containerId;
    this.sectionSelector = sectionSelector;
    this.sectionName = sectionName;
    this.handleAddSection = () => this.addSection();
    this.handleDeleteSection = (e) => this.deleteSection(e);

    this.container = document.getElementById(this.containerId);
    this.init();
};

Repeat.prototype.init = function () {
    this.order();
    this.initSortable();
    this.eventListeners();

    EventBus.subscribe('form-refreshed', (e) => {
        // Refetch the container because of refresh it is a new DOM reference.
        this.container = document.getElementById(this.containerId);

        if (!e.element.contains(this.container)) {
            return;
        }

        this.init();
    });

    // EventBus.subscribe('sidebarPanelActivated', () => {
    //     console.log('sisisi');
    //
    //     this.init();
    //
    //     // TODO: also clear events subscriptions when panel closed...
    //     // EventBus.subscribe('chief-form-submitted', (e) => {
    //     //         this.refreshIn(e.container, e.panel.getTags());
    //     //     });
    // });
};

Repeat.prototype.eventListeners = function () {
    this.container.querySelectorAll('[data-add-repeat-section]').forEach((el) => {
        el.removeEventListener('click', this.handleAddSection);
        el.addEventListener('click', this.handleAddSection);
    });

    this.container.querySelectorAll('[data-delete-repeat-section]').forEach((el) => {
        el.removeEventListener('click', this.handleDeleteSection);
        el.addEventListener('click', this.handleDeleteSection);
    });
};

Repeat.prototype.addSection = function () {
    const nextIndex = this.container.querySelectorAll(this.sectionSelector).length;
    const url = `${this.endpoint}?index=${nextIndex}`;

    fetch(url)
        .then((response) => response.json())
        .then((json) => {
            this.insertSection(json.data);

            this.eventListeners();
        })
        .catch((error) => {
            console.error(error);
        });
};

Repeat.prototype.insertSection = function (sectionHtml) {
    const DOM = document.createElement('div');
    DOM.innerHTML = sectionHtml;

    const sectionElement = DOM.firstChild;
    const sections = this.container.querySelectorAll(this.sectionSelector);
    const lastSection = sections[sections.length - 1];

    this.container.insertBefore(sectionElement, lastSection.nextSibling);

    vueFields(sectionElement);
};

Repeat.prototype.deleteSection = function (e) {
    const section = e.currentTarget.closest(this.sectionSelector);

    if (section) {
        section.parentNode.removeChild(section);
    }
};

/**
 * Order every element query index by setting the index
 * according to its position in the DOM tree.
 */
Repeat.prototype.order = function () {
    let index = 0;

    if (!this.container) {
        console.error('container by id ' + this.containerId + ' not found.');
        return;
    }

    this.container.querySelectorAll(this.sectionSelector).forEach((el) => {
        el.querySelectorAll(`[name^="${this.sectionName}["]`).forEach((node) => {
            const indexedName = node
                .getAttribute('name')
                .replace(new RegExp(`${this.sectionName}\\[([0-9]+)\\]`, 'g'), `${this.sectionName}[${index}]`);

            node.setAttribute('name', indexedName);
        });

        index++;
    });
};

Repeat.prototype.initSortable = function () {
    // As a best practise we make sure we destroy any existing sortable instance first.
    const existingSortable = Sortable.get(this.container);
    if (existingSortable) {
        existingSortable.destroy();
    }

    Sortable.create(this.container, {
        group: this.containerId,
        handle: '[data-sortable-handle]',
        animation: 200,
        easing: 'cubic-bezier(0.87, 0, 0.13, 1)',
        onEnd: () => {
            this.order();
        },
    });
};

export { Repeat as default };
