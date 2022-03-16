<x-chief::page>
    <x-slot name="header">
        <x-chief-form::form id="pagetitle" />
    </x-slot>

    @foreach($forms->filterByPosition('main')->get() as $form)
        {{ $form->render() }}
    @endforeach

    <x-chief::fragments :owner="$model" />

    <x-slot name="sidebar">
        <x-chief::window.status />
        <x-chief::window.links />

        @foreach($forms->filterByPosition('sidebar')->get() as $form)
            {{ $form->render() }}
        @endforeach
    </x-slot>
</x-chief::page>
