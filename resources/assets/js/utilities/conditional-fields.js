import RadioFieldTrigger from './conditional-fields/RadioFieldTrigger';

const initConditionalFields = (
    formgroupAttribute = 'data-formgroup',
    formgroupTypeAttribute = 'data-formgroup-type',
    formgroupDataAttribute = 'data-formgroup-data'
) => {
    const formgroupElements = Array.from(document.querySelectorAll(`[${formgroupAttribute}]`));

    formgroupElements.forEach((formgroupElement) => {
        const formgroupType = formgroupElement.getAttribute(formgroupTypeAttribute);
        const formgroupData = JSON.parse(formgroupElement.getAttribute(formgroupDataAttribute));

        if (!formgroupType || !formgroupData) return;

        switch (formgroupType) {
            case 'radio':
                new RadioFieldTrigger(formgroupElement, formgroupData);
                break;
            default:
                console.error(`Conditional fields handling for formgroup type ${formgroupType} not implemented ...`);
        }
    });
};

export { initConditionalFields as default };
