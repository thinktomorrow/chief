import Sortable from 'sortablejs';
import EventBus from '../../utilities/EventBus';
import vueFields from './vue-fields';

function initRepeatFieldsIn(container) {
    const repeatContainerSelector = '[data-repeat]';

    Array.from(container.querySelectorAll(repeatContainerSelector)).forEach((repeatElement) => {
        new Repeat(
            repeatElement.dataset.repeatEndpoint,
            repeatElement.id,
            '[data-repeat-section]',
            repeatElement.dataset.repeatSectionName,
            repeatContainerSelector
        );
    });
}

const Repeat = function (endpoint, containerId, sectionSelector, sectionName, containerSelector) {
    this.endpoint = endpoint;
    this.containerId = containerId;
    this.sectionSelector = sectionSelector;
    this.sectionName = sectionName;
    this.containerSelector = containerSelector;

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
};

Repeat.prototype.eventListeners = function () {
    Array.from(this.container.querySelectorAll('[data-add-repeat-section]'))
        .filter((el) => el.closest(this.containerSelector) === this.container)
        .forEach((el) => {
            el.removeEventListener('click', this.handleAddSection);
            el.addEventListener('click', this.handleAddSection);
        });

    Array.from(this.container.querySelectorAll('[data-delete-repeat-section]'))
        .filter((el) => el.closest(this.containerSelector) === this.container)
        .forEach((el) => {
            el.removeEventListener('click', this.handleDeleteSection);
            el.addEventListener('click', this.handleDeleteSection);
        });
};

Repeat.prototype.addSection = function () {
    const nextIndex = this.container.querySelectorAll(this.sectionSelector).length;
    const url = `${this.endpoint}?index=${nextIndex}`;

    fetch(url)
        .then((response) => {
            if (!response.ok) throw response;
            return response.json();
        })
        .then((json) => {
            console.log(json);

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
    const sections = this.container.querySelectorAll(`:scope > ${this.sectionSelector}`);
    const lastSection = sections[sections.length - 1];

    this.container.insertBefore(sectionElement, lastSection.nextSibling);

    vueFields(sectionElement);

    initRepeatFieldsIn(sectionElement);
};

Repeat.prototype.deleteSection = function (e) {
    // We are not deleting the last one here guys.
    if (this.container.querySelectorAll(`:scope > ${this.sectionSelector}`).length < 2) {
        return;
    }

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
        console.error(`container by id ${this.containerId} not found.`);
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

export { Repeat as default, initRepeatFieldsIn };
