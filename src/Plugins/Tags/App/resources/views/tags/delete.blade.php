@php
    use Illuminate\Support\Str;
    $modalId = Str::random(10);
@endphp

@if ($tag->getUsages() > 0)
    <div>
        <button type="button" x-data x-on:click="$dispatch('open-dialog', { 'id': 'state-modal-{{ $modalId }}' })">
            <x-chief::icon-button icon="icon-trash" color="grey" class="bg-white text-grey-500 shadow-none" />
        </button>

        @push('portals')
            <x-chief::dialog.modal id="state-modal-{{ $modalId }}" title="Verwijder deze tag" size="xs">
                <form
                    id="delete-tag-modal-form-{{ $modalId }}"
                    method="POST"
                    action="{{ route('chief.tags.delete', $tag->getTagId()) }}"
                >
                    @csrf
                    @method('DELETE')
                </form>

                <div class="prose prose-dark prose-spacing">
                    <p>
                        Hiermee verwijder je
                        <b>{{ $tag->getLabel() }}</b>
                        . Ben je zeker? Als je deze tag verwijdert, verdwijnt deze ook van alle gekoppelde pagina's.
                    </p>
                </div>

                <x-slot name="footer">
                    <x-chief::dialog.modal.footer>
                        <x-chief-table::button form="delete-tag-modal-form-{{ $modalId }}" type="submit" variant="red">
                            Verwijder tag
                        </x-chief-table::button>
                    </x-chief::dialog.modal.footer>
                </x-slot>
            </x-chief::dialog.modal>
        @endpush
    </div>
@else
    <form
        id="delete-tag-form-{{ $tag->getTagId() }}"
        action="{{ route('chief.tags.delete', $tag->getTagId()) }}"
        method="POST"
    >
        @csrf
        @method('DELETE')

        <button type="submit" form="delete-tag-form-{{ $tag->getTagId() }}">
            <x-chief::icon-button icon="icon-trash" color="grey" class="bg-white text-grey-500 shadow-none" />
        </button>
    </form>
@endif
