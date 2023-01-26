@php
    $title = 'Audit van '. $causer->fullname;
@endphp

<x-chief::template :title="$title">
    <x-slot name="hero">
        <x-chief::template.hero :title="$title" class="max-w-3xl"/>
    </x-slot>

    <x-chief::template.grid class="max-w-3xl">
        @include('chief::admin.audit._rows')
    </x-chief::template.grid>
</x-chief::template>
