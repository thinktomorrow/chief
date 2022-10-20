import EventBus from '../../utilities/EventBus';
import InputFieldTrigger from './InputFieldTrigger';
import RadioFieldTrigger from './RadioFieldTrigger';
import CheckboxFieldTrigger from './CheckboxFieldTrigger';
import SelectFieldTrigger from './SelectFieldTrigger';
import MultiSelectFieldTrigger from './MultiSelectFieldTrigger';

/**
 * Initialize conditional fields functionality
 * Registers all event listeners for conditional field triggers
 * @param {String} formgroupAttribute
 * @param {String} formgroupTypeAttribute
 * @param {String} conditionalFieldsDataAttribute
 */
const initConditionalFieldsInContainer = (
    container = document,
    formgroupAttribute = 'data-field-key',
    formgroupTypeAttribute = 'data-field-type',
    conditionalFieldsDataAttribute = 'data-conditional-toggle'
) => {
    const formgroupElements = Array.from(container.querySelectorAll(`[${formgroupAttribute}]`));

    formgroupElements.forEach((element) => {
        const name = element.getAttribute(formgroupAttribute);
        const type = element.getAttribute(formgroupTypeAttribute);
        const conditionalFieldsData = JSON.parse(element.getAttribute(conditionalFieldsDataAttribute));

        // If any of the above is not present, don't initialize a ConditionalFieldTrigger for this formgroup
        if (!name || !type || !conditionalFieldsData) return;

        switch (type) {
            case 'input':
                new InputFieldTrigger(name, element, conditionalFieldsData);
                break;
            case 'radio':
                new RadioFieldTrigger(name, element, conditionalFieldsData);
                break;
            case 'checkbox':
                new CheckboxFieldTrigger(name, element, conditionalFieldsData);
                break;
            case 'select':
                new SelectFieldTrigger(name, element, conditionalFieldsData);
                break;
            case 'multiselect':
                new MultiSelectFieldTrigger(name, element, conditionalFieldsData);
                break;
            default:
                console.error(
                    /* eslint-disable-next-line */
                    `Error while trying to initialise conditional fields: Trigger handling for type ${type} is not implemented yet ...`
                );
        }
    });
};

const initConditionalFields = () => {
    initConditionalFieldsInContainer(document);

    EventBus.subscribe('sidebarPanelActivated', (e) => {
        initConditionalFieldsInContainer(e.panel.el);
    });
};

export { initConditionalFields as default };
