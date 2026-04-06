<x-chief::dialog.modal id="refresh-modal" title="Je sessie is verlopen" size="xs" class="z-200">
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

<x-chief::dialog.modal id="error-modal" title="Er is iets misgelopen" size="xs" class="z-200">
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
