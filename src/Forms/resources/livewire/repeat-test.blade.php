<div class="space-y-3">
    @foreach($rows as $i => $row)
        <div>
            <h3>{{ $row['title'] }}</h3>
            <button type="button" wire:click="removeRow({{ $i }})">delete</button>
        </div>

    @endforeach

    <button wire:click="addRow" type="button" data-add-repeat-section class="w-full btn btn-grey">
        <span class="inline-block w-full text-center">
            Voeg een extra blok toe
        </span>
    </button>
</div>
