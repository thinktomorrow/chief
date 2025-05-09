<div class="space-y-4">
    @foreach ($this->sites as $i => $site)
        <x-chief::callout :title="$site->name" wire:key="{{ $site->locale }}" variant="outline-white">
            <div class="space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="mt-[0.1875rem] flex items-center gap-2">
                        <p class="text-sm/6 font-medium text-grey-500">{{ $site->url }}</p>
                    </div>
                </div>

                <x-chief::form.fieldset rule="menu">
                    <x-chief::form.label for="menu">Menu</x-chief::form.label>
                    <x-chief::form.input.select id="menu" wire:model="form.{{ $site->locale }}.menu">
                        @foreach ($menus as $menu)
                            <option wire:key="menu-option-{{ $menu->id }}" value="{{ $menu->id }}">
                                {{ $menu->title }}
                            </option>
                        @endforeach
                    </x-chief::form.input.select>
                </x-chief::form.fieldset>
            </div>
        </x-chief::callout>
    @endforeach
</div>
