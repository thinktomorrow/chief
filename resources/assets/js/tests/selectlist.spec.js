import Selectlist from '../alpine-directives/selectlist';

// Mock Choices.js library
const createMockChoices = () => ({
    getValue: jest.fn(() => []),
    clearStore: jest.fn(),
    setChoices: jest.fn(),
    containerOuter: { element: { classList: { add: jest.fn(), remove: jest.fn() } } },
    input: {
        element: {
            focus: jest.fn(),
            addEventListener: jest.fn(),
            value: '',
            classList: { add: jest.fn(), remove: jest.fn() },
        },
        clearInput: jest.fn(),
        setWidth: jest.fn(),
    },
    _searchChoices: jest.fn(),
    clearInput: jest.fn(),
});

describe('Selectlist', () => {
    let config;
    let instance;
    let mockChoices;

    beforeEach(() => {
        mockChoices = createMockChoices();

        config = {
            selection: [],
            options: [
                { value: 1, label: 'Option 1' },
                { value: 2, label: 'Option 2' },
            ],
            grouped: false,
        };
        instance = Selectlist(config);
        instance.$el = { choices: mockChoices, addEventListener: jest.fn(), dispatchEvent: jest.fn() };
        instance.$dispatch = jest.fn();
        instance.$nextTick = (callback) => callback();
    });

    it('initializes with given config', () => {
        expect(instance.selection).toEqual(config.selection);
        expect(instance.options).toEqual(config.options);
        expect(instance.grouped).toBe(config.grouped);
    });

    it('filters options correctly when not grouped', () => {
        instance.selection = [1];
        expect(instance.filteredOptions).toEqual([{ value: 2, label: 'Option 2' }]);
    });

    it('filters options correctly when grouped', () => {
        config.grouped = true;
        config.options = [
            {
                group: 'Group 1',
                choices: [
                    { value: 1, label: 'Option 1' },
                    { value: 2, label: 'Option 2' },
                ],
            },
        ];
        instance = Selectlist(config);
        instance.selection = [1];
        expect(instance.filteredOptions).toEqual([
            {
                group: 'Group 1',
                choices: [{ value: 2, label: 'Option 2' }],
            },
        ]);
    });

    it('adds an item to the selection', () => {
        mockChoices.getValue.mockReturnValueOnce([{ value: 3, label: 'Option 3' }]);
        instance.addItem();
        expect(instance.selection).toEqual([{ value: 3, label: 'Option 3' }]);
        expect(instance.$dispatch).toHaveBeenCalledWith('select-list-change');
        expect(instance.$dispatch).toHaveBeenCalledWith('input', [{ value: 3, label: 'Option 3' }]);
    });

    it('removes an item from the selection', () => {
        instance.selection = [1];
        instance.removeItem(1);
        expect(instance.selection).toEqual([]);
        expect(instance.$dispatch).toHaveBeenCalledWith('select-list-change');
        expect(instance.$dispatch).toHaveBeenCalledWith('input', []);
    });

    it('shows the select box', () => {
        instance.showSelectBox();
        expect(mockChoices.containerOuter.element.classList.remove).toHaveBeenCalledWith('hidden');
        expect(mockChoices.input.element.focus).toHaveBeenCalled();
        expect(instance.showingSelectBox).toBe(true);
    });

    it('hides the select box', () => {
        instance.selection = [1];
        instance.hideSelectBox();
        expect(mockChoices.containerOuter.element.classList.add).toHaveBeenCalledWith('hidden');
        expect(instance.showingSelectBox).toBe(false);
        expect(instance.searchTerm).toBe('');
        expect(mockChoices.clearInput).toHaveBeenCalled();
    });

    it('sorts the selection', () => {
        const sortedSelection = [2, 1];
        instance.selection = [1, 2];
        instance.sortSelection(sortedSelection);
        expect(instance.selection).toEqual(sortedSelection);
        expect(instance.$dispatch).toHaveBeenCalledWith('select-list-change');
        expect(instance.$dispatch).toHaveBeenCalledWith('input', sortedSelection);
    });

    it('handles search term preservation', () => {
        instance.searchTerm = 'test';
        instance.preserveSearchTerm();
        instance.$el.dispatchEvent(new CustomEvent('search', { detail: { value: 'new term' } }));
        expect(instance.searchTerm).toBe('new term');
    });
});
