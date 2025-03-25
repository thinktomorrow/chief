<div class="divide-y divide-grey-100">
    @foreach ($this->sites as $i => $site)
        <div wire:key="{{ $site->locale }}" class="space-y-3 px-4 py-6">
            <div class="flex items-start justify-between gap-2">
                <div class="mt-[0.1875rem] flex items-center gap-2">
                    <h3 class="text-sm/6 font-medium text-grey-500">{{ $site->name }}</h3>
                </div>
            </div>

            <div class="w-full lg:w-1/2">
                <x-chief::form.fieldset rule="menu">
                    <x-chief::form.input.select wire:model="form.{{ $site->locale }}.menu">
                        @foreach ($menus as $menu)
                            <option
                                wire:key="menu-option-{{ $menu->id }}"
                                value="{{ $menu->id }}"
                            >
                                {{ $menu->title }}
                            </option>
                        @endforeach
                    </x-chief::form.input.select>
                </x-chief::form.fieldset>
            </div>
        </div>
    @endforeach
</div>
