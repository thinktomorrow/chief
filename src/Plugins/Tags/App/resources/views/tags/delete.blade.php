@php
    use Illuminate\Support\Str;
    $modalId = Str::random(10);
@endphp

@if ($tag->getUsages() > 0)
    <div>
        <x-chief::button
            type="button"
            x-data
            x-on:click="$dispatch('open-dialog', { 'id': 'state-modal-{{ $modalId }}' })"
            variant="outline-red"
            size="sm"
        >
            <x-chief::icon.delete />
        </x-chief::button>

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
                        <x-chief::button form="delete-tag-modal-form-{{ $modalId }}" type="submit" variant="red">
                            Verwijder tag
                        </x-chief::button>
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

        <x-chief::button type="submit" form="delete-tag-form-{{ $tag->getTagId() }}" variant="outline-red" size="sm">
            <x-chief::icon.delete />
        </x-chief::button>
    </form>
@endif
