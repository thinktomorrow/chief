<div data-slot="form-group" class="flex flex-wrap items-center gap-2">
    <x-chief::badge size="sm">#{{ $fragment->bookmark }}</x-chief::badge>

    <x-chief::button
        size="xs"
        x-copy="{
            content: '#{{ $fragment->bookmark }}',
            successContent: '#{{ $fragment->bookmark }} gekopieerd!'
        }"
    >
        <x-chief::icon.link />
        <span>Kopieer</span>
    </x-chief::button>
</div>
