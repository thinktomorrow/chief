<style type="text/css">
    .admin-toast-container {
        position: fixed;
        right: 0;
        left: 0;
        bottom: 1rem;
        z-index: 9999;
        display: flex;
        justify-content: center;
        pointer-events: none;
    }

    .admin-toast {
        display: flex;
        align-items: center;
        background-color: white;
        box-shadow: 0px 10px 15px -3px rgba(0,0,0,0.1);
        border-radius: 9999px;
        pointer-events: auto;
    }
    .admin-toast > *:not(:first-child) {
        border-left: 1px solid rgb(240, 240, 240);
    }

    .admin-toast-link {
        display: flex;
        align-items: center;
        line-height: 1;
        padding: 0.75rem 1rem;
        cursor: pointer;
        text-align: center;
        transition: 75ms all ease-in-out;
    }
    .admin-toast-link:hover {
        color: #6366F1;
    }
    .admin-toast-link > *:not(:first-child) {
        margin-left: 0.5rem;
    }

    @media (max-width: 640px) {
        .admin-toast-hide-on-mobile {
            display: none;
        }
    }
</style>

<div data-admin-toast class="admin-toast-container">
    <div class="admin-toast">
        @if($editUrl)
            <span>
                <a
                    href="{{ $editUrl }}"
                    title="Bewerk deze pagina in chief"
                    class="admin-toast-link"
                    target="_blank"
                    rel="noopener"
                >
                    <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"> <path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16" y1="8" x2="2" y2="22"/><line x1="17.5" y1="15" x2="9" y2="15"/> </svg>
                    <span class="admin-toast-hide-on-mobile">Pagina bewerken</span>
                </a>
            </span>
        @endif

        <span>
            <a
                href="{{ $toggleUrl }}"
                title="{{ $inPreviewMode ? 'Schakel preview uit: verberg offline pagina\'s' : 'Schakel preview aan: toon offline pagina\'s' }}"
                class="admin-toast-link"
                target="_blank"
                rel="noopener"
            >
                @if($inPreviewMode)
                    <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /> </svg>
                    <span>
                        <span>Verberg offline</span>
                        <span class="admin-toast-hide-on-mobile">pagina's en</span>
                        <span>items</span>
                    <span>
                @else
                    <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /> </svg>
                    <span>
                        <span>Toon offline</span>
                        <span class="admin-toast-hide-on-mobile">pagina's en</span>
                        <span>items</span>
                    <span>
                @endif
            </a>
        </span>

        <span>
            <span data-admin-toast-close title="sluiten" aria-label="close" class="admin-toast-link">
                <span class="admin-toast-hide-on-mobile">Sluiten</span>
                <svg width="16" height="16" style="margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"> <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/> </svg>
            </span>
        </span>
    </div>
</div>
