@php use Thinktomorrow\Chief\Forms\Livewire\LivewireFieldName; @endphp

<x-chief::input.select
    wire:model.lazy="{{ LivewireFieldName::get($getName(),$locale ?? null) }}"
    id="{{ $getElementId($locale ?? null) }}"
    name="{{ $getName($locale ?? null) . ($allowMultiple() ? '[]' : '') }}"
    :multiple="$allowMultiple()"
>
    <option value="">---</option>

    @foreach ($getOptions() as $key => $value)

        {{-- This allows to give a nested list of ['value' => 12, 'label' => 'title'] so this does not conflict with json reordering on livewire js. --}}
        @if(is_array($value))
            <option
                {{ in_array($value['value'], (array) $getActiveValue($locale ?? null)) ? 'selected' : '' }} value="{{ $value['value'] }}">
                {{ $value['label'] }}
            </option>
        @else
            <option {{ in_array($key, (array) $getActiveValue($locale ?? null)) ? 'selected' : '' }} value="{{ $key }}">
                {{ $value }}
            </option>
        @endif

    @endforeach
</x-chief::input.select>
