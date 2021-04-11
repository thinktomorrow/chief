@formgroup
    @slot('label', 'Label')
    @slot('description', 'Dit is de tekst die wordt getoond in het menu. Kies een korte, duidelijke term.')
    @slot('isRequired', true)

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
                            class="w-full"
                        >

                        @error('trans.' . $locale . '.label')
                            <x-inline-notification type="error">
                                {{ $message}}
                            </x-inline-notification>
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
                    class="w-full"
                >

                @error('trans.' . $locale . '.label')
                    <x-inline-notification type="error">
                        {{ $message}}
                    </x-inline-notification>
                @enderror
            </div>
        @endforeach
    @endif
@endformgroup
