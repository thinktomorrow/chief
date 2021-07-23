import RadioFieldTrigger from './conditional-fields/RadioFieldTrigger';
import InputFieldTrigger from './conditional-fields/InputFieldTrigger';

/**
 * Initialize conditional fields functionality
 * Registers all event listeners for conditional field triggers if present
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

    formgroupElements.forEach((formgroupElement) => {
        const formgroupName = formgroupElement.getAttribute(formgroupAttribute);
        const formgroupType = formgroupElement.getAttribute(formgroupTypeAttribute);
        const conditionalFieldsData = JSON.parse(formgroupElement.getAttribute(conditionalFieldsDataAttribute));

        // If any of the above is not present, don't initialize a conditional field trigger for this formgroup
        if (!formgroupName || !formgroupType || !conditionalFieldsData) return;

        switch (formgroupType) {
            case 'radio':
                new RadioFieldTrigger(formgroupName, formgroupElement, conditionalFieldsData);
                break;
            case 'input':
                new InputFieldTrigger(formgroupName, formgroupElement, conditionalFieldsData);
                break;
            default:
                console.error(`Conditional fields handling for formgroup type ${formgroupType} not implemented ...`);
        }
    });
};

export { initConditionalFields as default };
