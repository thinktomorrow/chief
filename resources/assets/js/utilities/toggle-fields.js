/**
 * Allows to toggle CSS classes for the target selectors.
 * Default class is "hidden" which respectively hides or shows the target element. (e.g. )
 *
 * @example
 * Clicking on this element will add class "hidden" to the element with
 * selector "#inputId" when the trigger value is equal to 'one'.
 *
 * data-toggle-fields="[{id: #inputId, values: [one]}]"
 *
 * @author Ben Cavens <https://github.com/BenCavens>
 */

// TODO: toggle off when other options of same field are selected (= the current one is deselected)
// TODO:  active selection on pageload to be shown

(function () {
    function handleToggle() {
        getTargetElements(this).forEach((target) => {
            if (target.classList.contains('hidden')) {
                showTarget(target);
            } else {
                hideTarget(target);
            }
        });

        // Allow to proceed with other click events
        return true;
    }

    function getTriggerElements() {
        return document.querySelectorAll('[data-toggle-field-trigger]');
    }

    function getTargetElements(triggerElement) {
        const targetSelectors = triggerElement.getAttribute('data-toggle-field-trigger').split(',');
        let targets = [];

        targetSelectors.forEach((targetSelector) => {
            targets = [...targets, ...document.querySelectorAll(`[data-toggle-field-target="${targetSelector}"]`)];
        });

        return targets;
    }

    function hideOnPageLoad() {
        // Init all to hidden
        getTriggerElements().forEach((triggerElement) => {
            getTargetElements(triggerElement).forEach((targetElement) => {
                hideTarget(targetElement);
            });
        });

        // except current selected ones
        // TODO: toggle within same group
    }

    function hideTarget(element) {
        element.classList.add('hidden');
    }

    function showTarget(element) {
        element.classList.remove('hidden');
    }

    window.registerFieldToggles = function () {
        const triggers = getTriggerElements();

        Array.from(triggers).forEach((trigger) => {
            trigger.removeEventListener('click', handleToggle);
        });

        Array.from(triggers).forEach((trigger) => {
            trigger.addEventListener('click', handleToggle);
        });

        // Initial pageload
        hideOnPageLoad();
    };

    window.registerFieldToggles();
})();
