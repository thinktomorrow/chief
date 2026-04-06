const SESSION_EXPIRED_RELOAD_KEY = 'chief:livewire:419-reload-at';
const SESSION_EXPIRED_RELOAD_COOLDOWN_MS = 2 * 60 * 1000;
const KEEP_ALIVE_INTERVAL_MS = 10 * 60 * 1000;

let hasRegisteredLivewireRequestHook = false;
let keepAliveTimerId = null;

const getConfig = () => {
    return window.chiefSessionHandlerConfig || {};
};

const shouldAutoReloadOnSessionExpired = () => {
    const now = Date.now();
    const lastReloadAt = Number.parseInt(sessionStorage.getItem(SESSION_EXPIRED_RELOAD_KEY) || '0', 10);

    if (Number.isFinite(lastReloadAt) && now - lastReloadAt < SESSION_EXPIRED_RELOAD_COOLDOWN_MS) {
        return false;
    }

    sessionStorage.setItem(SESSION_EXPIRED_RELOAD_KEY, String(now));

    return true;
};

const openDialog = (id) => {
    window.dispatchEvent(new CustomEvent('open-dialog', { detail: { id } }));
};

const startKeepAlive = () => {
    if (keepAliveTimerId) {
        return;
    }

    const keepSessionAlive = () => {
        const pingUrl = getConfig().pingUrl || '/admin/session/ping';

        fetch(pingUrl, {
            method: 'GET',
            credentials: 'same-origin',
            cache: 'no-store',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        }).catch(() => {
            // Keep-alive failures should never block the admin UI.
        });
    };

    keepAliveTimerId = window.setInterval(keepSessionAlive, KEEP_ALIVE_INTERVAL_MS);
};

const registerLivewireRequestHook = () => {
    if (hasRegisteredLivewireRequestHook || !window.Livewire?.hook) {
        return;
    }

    hasRegisteredLivewireRequestHook = true;

    window.Livewire.hook('request', ({ fail }) => {
        fail(({ status, preventDefault }) => {
            if (getConfig().debug) {
                return;
            }

            if (status === 419) {
                preventDefault();

                if (shouldAutoReloadOnSessionExpired()) {
                    window.location.reload();

                    return;
                }

                openDialog('refresh-modal');

                return;
            }

            if (status >= 500) {
                preventDefault();
                openDialog('error-modal');
            }
        });
    });
};

const initLivewireSessionHandler = () => {
    if (window.__chiefLivewireSessionHandlerInitialized) {
        return;
    }

    window.__chiefLivewireSessionHandlerInitialized = true;

    startKeepAlive();

    if (window.Livewire) {
        registerLivewireRequestHook();
    }

    document.addEventListener('livewire:init', registerLivewireRequestHook);
};

initLivewireSessionHandler();
