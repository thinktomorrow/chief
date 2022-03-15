<x-chief-form::formgroup.wrapper id="label" label="Label" required>
    <x-slot name="description">
        <p>Dit is de tekst die wordt getoond in het menu. Kies een korte, duidelijke term.</p>
    </x-slot>

    @if(count(config('chief.locales')) > 1)
        <tabs v-cloak>
            @foreach(config('chief.locales') as $locale)
                <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}')}">
                    <div class="space-y-2">
                        <input
                                type="text"
                                name="trans[{{ $locale }}][label]"
                                id="trans-{{ $locale }}-label"
                                placeholder="Menu label"
                                value="{{ old('trans.'.$locale.'.label', $menuitem->dynamic('label', $locale)) }}"
                        >

                        @error('trans.' . $locale . '.label')
                        <x-chief-inline-notification type="error">
                            {{ $message}}
                        </x-chief-inline-notification>
                        @enderror
                    </div>
                </tab>
            @endforeach
        </tabs>
    @else
        @foreach(config('chief.locales') as $locale)
            <div class="space-y-2">
                <input
                        type="text"
                        name="trans[{{ $locale }}][label]"
                        id="trans-{{ $locale }}-label"
                        placeholder="Menu label"
                        value="{{ old('trans.'.$locale.'.label', $menuitem->dynamic('label', $locale)) }}"
                >

                @error('trans.' . $locale . '.label')
                <x-chief-inline-notification type="error">
                    {{ $message}}
                </x-chief-inline-notification>
                @enderror
            </div>
        @endforeach
    @endif
</x-chief-form::formgroup.wrapper>
