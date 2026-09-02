const SESSION_EXPIRED_RELOAD_KEY = 'chief:livewire:419-reload-at';

const importHandler = async ({ debug = false } = {}) => {
    let requestInterceptor;

    window.chiefSessionHandlerConfig = {
        debug,
        pingUrl: '/admin/session/ping',
    };

    window.Livewire = {
        interceptRequest: jest.fn((callback) => {
            requestInterceptor = callback;
        }),
    };

    await import('../livewire-session-handler');

    let errorHandler;

    requestInterceptor({
        onError: (callback) => {
            errorHandler = callback;
        },
    });

    return errorHandler;
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

        const errorHandler = await importHandler({ debug: true });

        errorHandler({ response: { status: 419 }, preventDefault });

        expect(preventDefault).toHaveBeenCalled();
        expect(openedDialogs).toEqual(['refresh-modal']);
    });

    it('keeps native Livewire error handling for server errors in debug mode', async () => {
        const preventDefault = jest.fn();
        const errorHandler = await importHandler({ debug: true });

        errorHandler({ response: { status: 500 }, preventDefault });

        expect(preventDefault).not.toHaveBeenCalled();
    });

    it('opens the generic error dialog for server errors outside debug mode', async () => {
        const preventDefault = jest.fn();
        const openedDialogs = [];

        window.addEventListener('open-dialog', (event) => openedDialogs.push(event.detail.id));

        const errorHandler = await importHandler({ debug: false });

        errorHandler({ response: { status: 500 }, preventDefault });

        expect(preventDefault).toHaveBeenCalled();
        expect(openedDialogs).toEqual(['error-modal']);
    });
});
