@php
    use Thinktomorrow\Chief\Fragments\App\Queries\GetOwners;
    use Thinktomorrow\Chief\Fragments\Fragment;
    use Thinktomorrow\Chief\Fragments\FragmentStatus;
@endphp


<x-chief::dialog.modal wired size="xxs">
    @if ($isOpen)

        <x-slot name="title">
            {{ ucfirst($fragment->label) }}
        </x-slot>

        <x-slot name="subtitle">
            @include('chief-fragments::livewire._partials.bookmark')
        </x-slot>

        <div class="space-y-4">
            <div class="prose prose-dark prose-spacing">
                Dit is wat inhoud...
            </div>

            @foreach ($this->getFields() as $field)
                {{ $field }}
            @endforeach
        </div>

        @include('chief-fragments::livewire._partials.shared-fragment-actions')
        @include('chief-fragments::livewire._partials.status-fragment-actions')

        <x-slot name="footer">
            <x-chief-table::button wire:click="close" class="shrink-0">Annuleer</x-chief-table::button>
            <x-chief-table::button wire:click="save" variant="blue" class="shrink-0">
                Bewaren
            </x-chief-table::button>
        </x-slot>
    @endif
</x-chief::dialog.modal>
