<x-chief::form.fieldset class="w-full space-y-3" rule="allowed_sites">
    <x-chief::form.label for="allowed_sites" required>
        Op welke sites wil je de nieuwe pagina tonen?
    </x-chief::form.label>

    @foreach (\Thinktomorrow\Chief\Sites\ChiefSites::all() as $site)
        <label
            for="{{ $site->locale }}"
            @class([
                'flex items-start gap-3 rounded-xl border border-grey-200 p-4',
                '[&:has(input[type=checkbox]:checked)]:border-blue-200 [&:has(input[type=checkbox]:checked)]:bg-blue-50',
            ])
        >
            <x-chief::form.input.checkbox
                wire:model.change="allowed_sites"
                id="{{ $site->locale }}"
                value="{{ $site->locale }}"
                class="shrink-0"
            />

            <div class="flex grow items-start justify-between gap-2">
                <div class="space-y-2">
                    <p class="font-medium leading-5 text-grey-700">
                        {{ $site->name }}:
                        <span class="leading-5 text-grey-400">{{ $site->url }}</span>
                    </p>
                </div>
            </div>
        </label>
    @endforeach
</x-chief::form.fieldset>
