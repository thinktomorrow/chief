<x-chief::dialog id="refresh-modal" title="Je sessie is verlopen" size="xs">
    <div class="prose prose-dark prose-spacing">
        <p>
            Deze pagina was te lang inactief.
            Klik op de knop hieronder om de pagina opnieuw te laden.
        </p>
    </div>

    <x-slot name="footer" class="flex justify-end">
        <button x-on:click="window.location.reload()" class="btn btn-primary">
            Herlaad de pagina
        </button>
    </x-slot>
</x-chief::dialog>

@push('custom-scripts-after-vue')
    <script>
        Livewire.onError((message, response) => {
            if (response.status === 419) {
                window.dispatchEvent(new CustomEvent('open-dialog', {detail: {id: 'refresh-modal'}}));

                return false;
            }
        });
    </script>
@endpush
