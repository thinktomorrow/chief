<x-chief::dialog.modal id="refresh-modal" title="Je sessie is verlopen" size="xs">
    <div class="prose prose-dark prose-spacing">
        <p>Deze pagina was te lang inactief. Klik op de knop hieronder om de pagina opnieuw te laden.</p>
    </div>

    <x-slot name="footer">
        <x-chief::dialog.modal.footer>
            <x-chief::button variant="blue" type="button" x-on:click="window.location.reload()">
                Herlaad de pagina
            </x-chief::button>
        </x-chief::dialog.modal.footer>
    </x-slot>
</x-chief::dialog.modal>

<x-chief::dialog.modal id="error-modal" title="Er is iets misgelopen" size="xs">
    <div class="prose prose-dark prose-spacing">
        <p>
            Er is een onverwachte fout opgetreden. Klik op de knop hieronder om de pagina opnieuw te laden.
        </p>
    </div>

    <x-slot name="footer">
        <x-chief::dialog.modal.footer>
            <x-chief::button variant="blue" type="button" x-on:click="window.location.reload()">
                Herlaad de pagina
            </x-chief::button>
        </x-chief::dialog.modal.footer>
    </x-slot>
</x-chief::dialog.modal>

@push('custom-scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            window.Livewire.hook('request', ({ fail }) => {
                fail(({ status, preventDefault }) => {

                    // In debug mode, let Livewire handle the error to show the detailed error page
                    if ({{ config('app.debug') ? 'true' : 'false' }}) {
                        return true;
                    }

                    // Handle 419 errors - Show refresh modal
                    if (status === 419) {
                        preventDefault();
                        window.dispatchEvent(new CustomEvent('open-dialog', { detail: { id: 'refresh-modal' } }));

                        return false;
                    }

                    // Handle 500 errors - Show Chief error page
                    if (status >= 500) {

                        preventDefault();

                        window.dispatchEvent(new CustomEvent('open-dialog', { detail: { id: 'error-modal' } }));

                        return false;
                    }

                    // Handle other errors via default Livewire behavior
                    return true;
                });
            });
        });
    </script>
@endpush
