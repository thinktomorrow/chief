@if (count($sites) > 1)
    <x-chief::form.input.select wire:model.live.change="scopedLocale">
        @foreach ($sites as $site)
            <option value="{{ $site->locale }}">{{ $site->name }}</option>
        @endforeach
    </x-chief::form.input.select>
@endif
