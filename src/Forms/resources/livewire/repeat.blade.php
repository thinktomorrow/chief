<div class="space-y-3">

{{--    <span>COUNT: {{ $count }}</span>--}}

{{--    @if($this->field->getId() == 'inner')--}}
{{--        {{ dd($this->field->getRepeatedComponents()) }}--}}
{{--    @endif--}}

    @foreach($this->rows as $i => $row)
        <div wire:key="repeat-{{$this->field->getId()}}-{{ $i }}" class="flex gap-3 p-3 border rounded-lg border-grey-100">
            <span class="cursor-pointer shrink-0">
                <x-chief::icon-button icon="icon-chevron-up-down" color="grey" />
            </span>

            <div class="w-full my-1 space-y-4">
                @foreach($row as $j => $childComponent)
                    @if($childComponent instanceof \Thinktomorrow\Chief\Forms\Fields\Repeat)
                        {{ $childComponent->parentField($this->field) }}
                    @else
                        {{ $childComponent }}
                    @endif
                @endforeach
            </div>

            <span wire:click="removeRow({{ $i }})" class="cursor-pointer shrink-0">
                <x-chief::icon-button icon="icon-trash" color="grey" />
            </span>
        </div>
    @endforeach

    <button wire:click="addRow" type="button" class="w-full btn btn-grey">
        <span class="inline-block w-full text-center">
            Voeg een extra blok toe
        </span>
    </button>
</div>
