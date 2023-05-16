/**
 * Allows to toggle CSS classes for the target selectors.
 * Default class is "hidden" which respectively hides or shows the target element. (e.g. )
 *
 * @example
 * // Clicking on this element will add class "hidden" to the element with selector "#dropdown"
 * data-toggle-class="#dropdown"
 * @example
 * // Specific class given to add to the element
 * data-toggle-class="#dropdown,hidden"
 * @example
 * // Multiple targets elements for classes to be added
 * data-toggle-class="#dropdown,hidden|#backdrop"
 *
 * @author Ben Cavens <https://github.com/BenCavens>
 */
const handleToggle = function (e) {
    e.preventDefault();

    const trigger = this;
    const targetSelectors = trigger.getAttribute('data-toggle-class').split('|');

    targetSelectors.forEach((targetSelector) => {
        const targets = document.querySelectorAll(targetSelector.split(',')[0]);
        const className = targetSelector.includes(',') ? targetSelector.split(',')[1] : 'hidden';
        const triggerClassName = 'active';

        Array.from(targets).forEach((target) => {
            if (target.classList.contains(className)) {
                trigger.classList.add(triggerClassName);
                target.classList.remove(className);
            } else {
                trigger.classList.remove(triggerClassName);
                target.classList.add(className);
            }
        });
    });
};

const registerClassToggles = () => {
    const toggles = Array.from(document.querySelectorAll('[data-toggle-class]'));

    toggles.forEach((trigger) => {
        trigger.removeEventListener('click', handleToggle);
    });

    toggles.forEach((trigger) => {
        trigger.addEventListener('click', handleToggle);
    });
};

export { registerClassToggles as default };
