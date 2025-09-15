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
        box-shadow: 0px 4px 8px 0px rgba(0, 0, 0, 0.1);
        border-radius: 24px;
        pointer-events: auto;
        border: 1px solid oklch(0.968 0.007 247.896);
    }
    .admin-toast > *:not(:first-child) {
        border-left: 1px solid oklch(0.968 0.007 247.896);
    }
    .admin-toast-link {
        color: oklch(0.129 0.042 264.695);
        display: flex;
        align-items: start;
        gap: 0.25rem;
        line-height: 1.25;
        padding: 0.5rem 0.75rem;
        cursor: pointer;
        transition: 75ms all ease-in-out;
    }
    .admin-toast-link:hover {
        color: oklch(0.5113 0.2298 277.28);
    }
    .admin-toast-link > svg {
        flex-shrink: 0;
    }
    @media (max-width: 768px) {
        .admin-toast-hide-on-mobile {
            display: none;
        }
    }
</style>

<div data-admin-toast class="admin-toast-container">
    <div class="admin-toast">
        @if ($editUrl)
            <a href="{{ $editUrl }}" class="admin-toast-link">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="20"
                    height="20"
                    color="currentColor"
                    fill="none"
                >
                    <path
                        d="M5.07579 17C4.08939 4.54502 12.9123 1.0121 19.9734 2.22417C20.2585 6.35185 18.2389 7.89748 14.3926 8.61125C15.1353 9.38731 16.4477 10.3639 16.3061 11.5847C16.2054 12.4534 15.6154 12.8797 14.4355 13.7322C11.8497 15.6004 8.85421 16.7785 5.07579 17Z"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                    <path
                        d="M4 22C4 15.5 7.84848 12.1818 10.5 10"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
                <span class="admin-toast-hide-on-mobile">Pagina bewerken</span>
            </a>
        @endif

        <a href="{{ $toggleUrl }}" class="admin-toast-link">
            @if ($inPreviewMode)
                <span
                    style="
                        color: oklch(0.553 0.195 38.402);
                        display: inline-block;
                        font-size: 12px;
                        line-height: 16px;
                        font-weight: 500;
                        padding: 4px 8px;
                        background-color: oklch(0.98 0.016 73.684);
                        border-radius: 6px;
                        margin: -2px 0;
                    "
                >
                    Offline items zichtbaar
                </span>
                <svg viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none">
                    <path
                        d="M19.439 15.439C20.3636 14.5212 21.0775 13.6091 21.544 12.955C21.848 12.5287 22 12.3155 22 12C22 11.6845 21.848 11.4713 21.544 11.045C20.1779 9.12944 16.6892 5 12 5C11.0922 5 10.2294 5.15476 9.41827 5.41827M6.74742 6.74742C4.73118 8.1072 3.24215 9.94266 2.45604 11.045C2.15201 11.4713 2 11.6845 2 12C2 12.3155 2.15201 12.5287 2.45604 12.955C3.8221 14.8706 7.31078 19 12 19C13.9908 19 15.7651 18.2557 17.2526 17.2526"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    ></path>
                    <path
                        d="M9.85786 10C9.32783 10.53 9 11.2623 9 12.0711C9 13.6887 10.3113 15 11.9289 15C12.7377 15 13.47 14.6722 14 14.1421"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                    ></path>
                    <path
                        d="M3 3L21 21"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    ></path>
                </svg>
                <span>
                    <span>Verberg offline</span>
                    <span class="admin-toast-hide-on-mobile">pagina's en</span>
                    <span>items</span>
                </span>
            @else
                <svg viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none">
                    <path
                        d="M21.544 11.045C21.848 11.4713 22 11.6845 22 12C22 12.3155 21.848 12.5287 21.544 12.955C20.1779 14.8706 16.6892 19 12 19C7.31078 19 3.8221 14.8706 2.45604 12.955C2.15201 12.5287 2 12.3155 2 12C2 11.6845 2.15201 11.4713 2.45604 11.045C3.8221 9.12944 7.31078 5 12 5C16.6892 5 20.1779 9.12944 21.544 11.045Z"
                        stroke="currentColor"
                        stroke-width="1.5"
                    />
                    <path
                        d="M15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15C13.6569 15 15 13.6569 15 12Z"
                        stroke="currentColor"
                        stroke-width="1.5"
                    />
                </svg>
                <span>
                    <span>Toon offline</span>
                    <span class="admin-toast-hide-on-mobile">pagina's en</span>
                    <span>items</span>
                </span>
            @endif
        </a>

        <button type="button" data-admin-toast-close aria-label="close" class="admin-toast-link">
            <span class="admin-toast-hide-on-mobile">Sluiten</span>
            <svg viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none">
                <path
                    d="M18 6L6.00081 17.9992M17.9992 18L6 6.00085"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                ></path>
            </svg>
        </button>
    </div>
</div>
