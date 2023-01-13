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

const Repeat = function (endpoint, containerId, sectionSelector, prefix, containerSelector) {
    this.endpoint = endpoint;
    this.containerId = containerId;
    this.sectionSelector = sectionSelector;
    this.prefix = prefix;
    this.containerSelector = containerSelector;

    this.handleAddSection = () => this.addSection();
    this.handleDeleteSection = (e) => this.deleteSection(e);

    this.container = document.getElementById(this.containerId);

    if (!this.container) {
        console.error(`container by id ${this.containerId} not found.`);
        return;
    }

    this.init();

    EventBus.subscribe('chief-repeat-index-changed', (data) => {
        if (data.section.contains(this.container) && this.prefix.startsWith(data.former_prefix)) {
            this.prefix = this.prefix.replace(data.former_prefix, data.new_prefix);
        }
    });

    EventBus.subscribe('chief-form-refreshed', (e) => {
        // Refetch the container because of refresh it is a new DOM reference.
        this.container = document.getElementById(this.containerId);

        if (!e.element.contains(this.container)) {
            return;
        }

        this.init();
    });
};

Repeat.prototype.init = function () {
    this.order();
    this.initSortable();
    this.eventListeners();
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
    const nextIndex = this.getSections().length;

    const url = `${this.endpoint}?index=${nextIndex}&prefix=${this.prefix}`;

    fetch(url)
        .then((response) => {
            if (!response.ok) throw response;
            return response.json();
        })
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
    const sections = this.getSections();
    const lastSection = sections[sections.length - 1];

    // Place after last section - if no section present yet, add it as the first section in the container
    if (lastSection) {
        this.container.insertBefore(sectionElement, lastSection.nextSibling);
    } else {
        this.container.prepend(sectionElement);
    }

    vueFields(sectionElement);

    initRepeatFieldsIn(sectionElement);
};

Repeat.prototype.deleteSection = function (e) {
    const section = e.currentTarget.closest(this.sectionSelector);

    if (section) {
        section.parentNode.removeChild(section);
    }
};

Repeat.prototype.getSections = function () {
    return this.container.querySelectorAll(`:scope > ${this.sectionSelector}`);
};

/**
 * Order every element query index by setting the index
 * according to its position in the DOM tree.
 */
Repeat.prototype.order = function () {
    let index = 0;

    this.getSections().forEach((section) => {
        // TODO: for each locale input, this will be executed. We could prevent this.
        Array.from(section.querySelectorAll(`[name^="${this.prefix}["]`))
            // .filter((_el) => _el.closest(this.containerSelector) === this.container)
            .forEach((node) => {
                const match = node
                    .getAttribute('name')
                    .match(new RegExp(`${this.escapeForRegExp(this.prefix)}\\[([0-9]+)\\]`, 'g'));

                if (!match) {
                    console.error(this.prefix);
                }

                if (!match) return;

                const formerPrefix = match[0];
                const indexedName = node.getAttribute('name').replace(formerPrefix, `${this.prefix}[${index}]`);

                EventBus.publish('chief-repeat-index-changed', {
                    section,
                    former_prefix: formerPrefix,
                    new_prefix: `${this.prefix}[${index}]`,
                });

                node.setAttribute('name', indexedName);
            });

        index++;
    });
};

Repeat.prototype.escapeForRegExp = function (string) {
    // eslint-disable-next-line
    return string.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
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
