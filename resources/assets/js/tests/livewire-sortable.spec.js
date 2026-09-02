jest.mock('sortablejs', () => ({
    __esModule: true,
    default: {
        create: jest.fn(),
    },
}));
jest.mock('../sortable/sortable-init', () => jest.fn());
jest.mock('../alpine-directives/sortable', () => jest.fn());

import Sortable from 'sortablejs';

describe('Livewire sortable directive', () => {
    it('registers after Livewire initializes and destroys the sortable instance on cleanup', async () => {
        let directiveHandler;
        let cleanupHandler;
        const sortableInstance = { destroy: jest.fn() };

        Sortable.create.mockReturnValue(sortableInstance);
        window.Alpine = { directive: jest.fn() };
        delete window.Livewire;

        await import('../sortable/index');

        window.Livewire = {
            directive: jest.fn((name, handler) => {
                directiveHandler = handler;
            }),
        };
        document.dispatchEvent(new CustomEvent('livewire:init'));

        expect(window.Livewire.directive).toHaveBeenCalledWith('sortable', expect.any(Function));

        const element = document.createElement('div');

        directiveHandler({
            el: element,
            cleanup: (handler) => {
                cleanupHandler = handler;
            },
        });

        expect(Sortable.create).toHaveBeenCalledWith(
            element,
            expect.objectContaining({ dataIdAttr: 'wire:sortable.item' })
        );

        cleanupHandler();

        expect(sortableInstance.destroy).toHaveBeenCalledTimes(1);
    });
});
