import Container from './Container';
import PanelsManager from './PanelsManager';
import { IndexSorting } from '../utilities/sortable';

/**
 * Fragments JS
 */
document.addEventListener('DOMContentLoaded', function () {
    const sidebarContainerEl = document.querySelector('#js-sidebar-container');
    const componentEl = document.querySelector('[data-fragments-component]');

    // Do not trigger the sidebar script is DOM element isn't present
    if (!sidebarContainerEl || !componentEl) return;

    const livewireComponent = Livewire.find(componentEl.getAttribute('wire:id'));

    const fragmentPanelsManager = new PanelsManager(
        '[data-sidebar-fragments-edit]',
        new Container(sidebarContainerEl),
        function (panel) {
            console.log('New fragments panel ' + panel.id);

            let fragmentSelectionElement = document.querySelector('[data-fragment-selection]');
            if (fragmentSelectionElement) {
                let order = getChildIndex(fragmentSelectionElement);
                panel.el.querySelector('input[name="order"]').value = order;
            }

            initSortable('[data-sortable-fragments]', panel.el);
        },
        function () {
            livewireComponent.reload();

            // TODO: set this in callback for when entire sidebar is closed.
            initSortable();
        }
    );

    fragmentPanelsManager.init();

    Livewire.on('fragmentsReloaded', () => {
        fragmentPanelsManager.scanForPanelTriggers();

        scanForFragmentSelectionTriggers();
    });

    function initSortable(selector = '[data-sortable-fragments]', container = document, options = {}) {
        // TODO: first remove existing sortable instances on these same selector els...
        Array.from(container.querySelectorAll(selector)).forEach((el) => {
            new IndexSorting({
                ...{
                    sortableGroupEl: el,
                    endpoint: el.getAttribute('data-sortable-endpoint'),
                    handle: '[data-sortable-handle]',
                    isSorting: true,
                },
                ...options,
            });
        });
    }

    initSortable();

    /**
     * Fragment selection
     */
    scanForFragmentSelectionTriggers();

    function scanForFragmentSelectionTriggers() {
        let fragmentSelectionTriggers = Array.from(document.querySelectorAll('[data-sortable-insert]'));

        fragmentSelectionTriggers.forEach((trigger) => {
            trigger.addEventListener('click', function () {
                let fragmentSelectionElement = document.querySelector('[data-fragment-selection]');

                if (fragmentSelectionElement) {
                    fragmentSelectionElement.parentElement.removeChild(fragmentSelectionElement);
                }

                fragmentSelectionElement = createFragmentSelection();

                insertFragmentSelectionElement(fragmentSelectionElement, trigger);

                fragmentPanelsManager.scanForPanelTriggers();
            });
        });
    }

    function createFragmentSelection() {
        const template = document.querySelector('#js-fragment-selection-template');
        const docFragment = document.importNode(template.content, true);
        const el = docFragment.firstElementChild;

        return el;
    }

    function insertFragmentSelectionElement(element, trigger) {
        let insertBeforeTarget = trigger.getAttribute('data-sortable-insert-position') === 'before';
        let targetElement = document.querySelector(
            `[data-sortable-id="${trigger.getAttribute('data-sortable-insert')}"]`
        );

        if (insertBeforeTarget) {
            targetElement.parentNode.insertBefore(element, targetElement);
        } else {
            targetElement.parentNode.insertBefore(element, targetElement.nextSibling);
        }
    }

    function getChildIndex(node) {
        return Array.prototype.indexOf.call(node.parentElement.children, node);
    }
});
