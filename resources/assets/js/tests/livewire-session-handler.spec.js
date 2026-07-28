const SESSION_EXPIRED_RELOAD_KEY = 'chief:livewire:419-reload-at';

const importHandler = async ({ debug = false } = {}) => {
    let requestHook;

    window.chiefSessionHandlerConfig = {
        debug,
        pingUrl: '/admin/session/ping',
    };

    window.Livewire = {
        hook: jest.fn((name, callback) => {
            if (name === 'request') {
                requestHook = callback;
            }
        }),
    };

    await import('../livewire-session-handler');

    let failHandler;

    requestHook({
        fail: (callback) => {
            failHandler = callback;
        },
    });

    return failHandler;
};

describe('Livewire session handler', () => {
    beforeEach(() => {
        jest.resetModules();
        jest.useFakeTimers();

        document.body.innerHTML = '';
        sessionStorage.clear();

        delete window.__chiefLivewireSessionHandlerInitialized;
        delete window.chiefSessionHandlerConfig;
        delete window.Livewire;

        window.fetch = jest.fn().mockResolvedValue({});
    });

    afterEach(() => {
        jest.useRealTimers();
    });

    it('prevents the native Livewire expired prompt in debug mode', async () => {
        const preventDefault = jest.fn();
        const openedDialogs = [];

        sessionStorage.setItem(SESSION_EXPIRED_RELOAD_KEY, String(Date.now()));
        window.addEventListener('open-dialog', (event) => openedDialogs.push(event.detail.id));

        const failHandler = await importHandler({ debug: true });

        failHandler({ status: 419, preventDefault });

        expect(preventDefault).toHaveBeenCalled();
        expect(openedDialogs).toEqual(['refresh-modal']);
    });

    it('keeps native Livewire error handling for server errors in debug mode', async () => {
        const preventDefault = jest.fn();
        const failHandler = await importHandler({ debug: true });

        failHandler({ status: 500, preventDefault });

        expect(preventDefault).not.toHaveBeenCalled();
    });

    it('opens the generic error dialog for server errors outside debug mode', async () => {
        const preventDefault = jest.fn();
        const openedDialogs = [];

        window.addEventListener('open-dialog', (event) => openedDialogs.push(event.detail.id));

        const failHandler = await importHandler({ debug: false });

        failHandler({ status: 500, preventDefault });

        expect(preventDefault).toHaveBeenCalled();
        expect(openedDialogs).toEqual(['error-modal']);
    });
});
