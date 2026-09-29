import BooleanFieldTrigger from '../forms/conditional-fields/BooleanFieldTrigger';

describe('BooleanFieldTrigger', () => {
    beforeAll(() => {
        global.CSS = { escape: (value) => value };
    });

    beforeEach(() => {
        jest.useFakeTimers();
        window.conditionalFieldsToggledByState = [];
        document.body.innerHTML = `
            <div data-field-key="manually_composed">
                <input type="checkbox" value="1" />
            </div>
            <div data-field-key="odoo_id"></div>
            <div data-field-key="preserve_non_odoo_variants"></div>
        `;
    });

    afterEach(() => {
        jest.useRealTimers();
    });

    it('shows fields when unchecked and hides them when checked', () => {
        const trigger = document.querySelector('[data-field-key="manually_composed"]');
        const checkbox = trigger.querySelector('input');
        const odooId = document.querySelector('[data-field-key="odoo_id"]');
        const preserveVariants = document.querySelector('[data-field-key="preserve_non_odoo_variants"]');

        new BooleanFieldTrigger('manually_composed', trigger, {
            odoo_id: ['0'],
            preserve_non_odoo_variants: ['0'],
        });

        jest.advanceTimersByTime(100);
        expect(odooId.classList.contains('hidden')).toBe(false);
        expect(preserveVariants.classList.contains('hidden')).toBe(false);

        checkbox.checked = true;
        checkbox.dispatchEvent(new Event('input', { bubbles: true }));
        jest.advanceTimersByTime(250);
        expect(odooId.classList.contains('hidden')).toBe(true);
        expect(preserveVariants.classList.contains('hidden')).toBe(true);

        checkbox.checked = false;
        checkbox.dispatchEvent(new Event('input', { bubbles: true }));
        jest.advanceTimersByTime(250);
        expect(odooId.classList.contains('hidden')).toBe(false);
        expect(preserveVariants.classList.contains('hidden')).toBe(false);
    });

    it('keeps positive boolean toggles working when checked initially', () => {
        const trigger = document.querySelector('[data-field-key="manually_composed"]');
        const checkbox = trigger.querySelector('input');
        const odooId = document.querySelector('[data-field-key="odoo_id"]');
        checkbox.checked = true;

        new BooleanFieldTrigger('manually_composed', trigger, { odoo_id: ['1'] });
        jest.advanceTimersByTime(100);
        expect(odooId.classList.contains('hidden')).toBe(false);

        checkbox.checked = false;
        checkbox.dispatchEvent(new Event('input', { bubbles: true }));
        jest.advanceTimersByTime(250);
        expect(odooId.classList.contains('hidden')).toBe(true);
    });
});
