import RadioFieldTrigger from './conditional-fields/RadioFieldTrigger';
import SelectFieldTrigger from './conditional-fields/SelectFieldTrigger';
import CheckboxFieldTrigger from './conditional-fields/CheckboxFieldTrigger';
import InputFieldTrigger from './conditional-fields/InputFieldTrigger';

/**
 * Initialize conditional fields functionality
 * Registers all event listeners for conditional field triggers
 * @param {String} formgroupAttribute
 * @param {String} formgroupTypeAttribute
 * @param {String} conditionalFieldsDataAttribute
 */
const initConditionalFields = (
    formgroupAttribute = 'data-conditional',
    formgroupTypeAttribute = 'data-conditional-trigger-type',
    conditionalFieldsDataAttribute = 'data-conditional-data'
) => {
    const formgroupElements = Array.from(document.querySelectorAll(`[${formgroupAttribute}]`));

    formgroupElements.forEach((element) => {
        const name = element.getAttribute(formgroupAttribute);
        const type = element.getAttribute(formgroupTypeAttribute);
        const conditionalFieldsData = JSON.parse(element.getAttribute(conditionalFieldsDataAttribute));

        // If any of the above is not present, don't initialize a conditional field trigger for this formgroup
        if (!name || !type || !conditionalFieldsData) return;

        switch (type) {
            case 'radio':
                new RadioFieldTrigger(name, element, conditionalFieldsData);
                break;
            case 'select':
                new SelectFieldTrigger(name, element, conditionalFieldsData);
                break;
            case 'checkbox':
                new CheckboxFieldTrigger(name, element, conditionalFieldsData);
                break;
            case 'input':
                new InputFieldTrigger(name, element, conditionalFieldsData);
                break;
            default:
                console.error(
                    /* eslint-disable-next-line */
                    `Error while trying to initialise conditional fields: Trigger handling for type ${type} is not implemented yet ...`
                );
        }
    });
};

export { initConditionalFields as default };
