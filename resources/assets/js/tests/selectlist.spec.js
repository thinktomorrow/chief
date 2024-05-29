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

        // Use a real EventTarget for $el to handle real event listeners
        instance.$el = new EventTarget();
        instance.$el.choices = mockChoices;
        instance.$el.addEventListener = EventTarget.prototype.addEventListener;
        instance.$el.dispatchEvent = EventTarget.prototype.dispatchEvent;

        instance.$dispatch = jest.fn();
        instance.$nextTick = (callback) => callback();
    });

    it('initializes with given config', () => {
        expect(instance.selection).toEqual(config.selection);
        expect(instance.options).toEqual(config.options);
        expect(instance.grouped).toBe(config.grouped);
    });

    it('excludes selection from options', () => {
        instance.selection = [1];
        expect(instance.filteredOptions).toEqual([{ value: 2, label: 'Option 2' }]);
    });

    it('excludes selection from grouped options', () => {
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

    it('handles non-existent options in selection for filteredOptions', () => {
        instance.selection = [{ value: 3 }];
        expect(instance.filteredOptions).toEqual([
            { value: 1, label: 'Option 1' },
            { value: 2, label: 'Option 2' },
        ]);
    });

    it('adds an item to the selection with duplicate values', () => {
        mockChoices.getValue.mockReturnValueOnce([{ value: 1, label: 'Option 1' }]);
        instance.selection = [{ value: 1, label: 'Option 1' }];
        instance.addItem();
        expect(instance.selection).toEqual([
            { value: 1, label: 'Option 1' },
            { value: 1, label: 'Option 1' },
        ]);
    });

    it('removes an item from the selection with a non-existent value', () => {
        instance.selection = [1];
        instance.removeItem(2);
        expect(instance.selection).toEqual([1]);
    });

    it('forces a search correctly', () => {
        instance.forceSearch('new search');
        expect(mockChoices.input.element.value).toBe('new search');
        expect(mockChoices.input.setWidth).toHaveBeenCalled();
        expect(mockChoices._searchChoices).toHaveBeenCalledWith('new search');
    });

    it('sorts the selection with an empty array', () => {
        instance.selection = [1, 2];
        instance.sortSelection([]);
        expect(instance.selection).toEqual([]);
        expect(instance.$dispatch).toHaveBeenCalledWith('select-list-change');
        expect(instance.$dispatch).toHaveBeenCalledWith('input', []);
    });
});
