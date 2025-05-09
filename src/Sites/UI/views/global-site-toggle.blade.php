<div class="flex justify-start gap-2">
    @if (count($sites) > 1)
        <x-chief::form.input.select wire:model.change="scopedLocale">
            @foreach ($sites as $site)
                <option value="{{ $site->locale }}">{{ $site->name }}</option>
            @endforeach
        </x-chief::form.input.select>
    @endif
</div>
