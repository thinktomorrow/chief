<x-chief::dialog.modal id="refresh-modal" title="Je sessie is verlopen" size="xs">
    <div class="prose prose-dark prose-spacing">
        <p>Deze pagina was te lang inactief. Klik op de knop hieronder om de pagina opnieuw te laden.</p>
    </div>

    <x-slot name="footer">
        <x-chief::dialog.modal.footer>
            <x-chief-table::button variant="blue" type="button" x-on:click="window.location.reload()">
                Herlaad de pagina
            </x-chief-table::button>
        </x-chief::dialog.modal.footer>
    </x-slot>
</x-chief::dialog.modal>

@push('custom-scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            window.Livewire.hook('request', ({ fail }) => {
                fail(({ status, preventDefault }) => {
                    if (status === 419) {
                        preventDefault();
                        window.dispatchEvent(new CustomEvent('open-dialog', { detail: { id: 'refresh-modal' } }));

                        return false;
                    }
                });
            });
        });
    </script>
@endpush
