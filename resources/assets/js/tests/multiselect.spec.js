import Choices from 'choices.js';
import multiselectDirective from '../alpine-directives/multiselect';

jest.mock('choices.js', () => jest.fn());

const createMockChoices = () => ({
    input: {
        element: {
            value: '',
        },
        setWidth: jest.fn(),
    },
    _searchChoices: jest.fn(),
});

describe('multiselectDirective', () => {
    let mockChoices;

    beforeEach(() => {
        mockChoices = createMockChoices();
        Choices.mockImplementation(() => mockChoices);
        jest.useFakeTimers();
    });

    afterEach(() => {
        jest.runOnlyPendingTimers();
        jest.useRealTimers();
    });

    it('preserves search term after selecting an item in multiselect', () => {
        const wrapper = document.createElement('div');
        const selectEl = document.createElement('select');
        selectEl.multiple = true;

        const evaluate = jest.fn(() => ({
            selectEl,
            options: {},
        }));

        multiselectDirective(wrapper, { expression: '{}' }, { evaluate, cleanup: jest.fn() });

        selectEl.dispatchEvent(new CustomEvent('search', { detail: { value: 'tag' }, bubbles: true }));
        selectEl.dispatchEvent(new Event('addItem', { bubbles: true }));

        jest.runAllTimers();

        expect(mockChoices.input.element.value).toBe('tag');
        expect(mockChoices.input.setWidth).toHaveBeenCalled();
        expect(mockChoices._searchChoices).toHaveBeenCalledWith('tag');
    });

    it('does not preserve search term for single select', () => {
        const wrapper = document.createElement('div');
        const selectEl = document.createElement('select');
        selectEl.multiple = false;

        const evaluate = jest.fn(() => ({
            selectEl,
            options: {},
        }));

        multiselectDirective(wrapper, { expression: '{}' }, { evaluate, cleanup: jest.fn() });

        selectEl.dispatchEvent(new CustomEvent('search', { detail: { value: 'tag' }, bubbles: true }));
        selectEl.dispatchEvent(new Event('addItem', { bubbles: true }));

        jest.runAllTimers();

        expect(mockChoices.input.setWidth).not.toHaveBeenCalled();
        expect(mockChoices._searchChoices).not.toHaveBeenCalled();
    });
});
