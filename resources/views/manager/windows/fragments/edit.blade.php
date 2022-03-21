<x-chief::sidebar-fragment>
    @foreach($forms->get() as $form)
        {{ $form->editInline()->showAsBlank()->render() }}
    @endforeach
</x-chief::sidebar-fragment>
