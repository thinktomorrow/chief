<x-chief::sidebar-fragment>
    @foreach($forms->get() as $form)
        {{ $form->tag('fragments')->editInline()->showAsBlank()->render() }}
    @endforeach
</x-chief::sidebar-fragment>
