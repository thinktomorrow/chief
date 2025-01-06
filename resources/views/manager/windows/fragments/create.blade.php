<x-chief-fragments::sidebar-fragment>
    @foreach ($forms->get() as $form)
        {{ $form->tag('fragments')->editInline()->showAsBlank()->render() }}
    @endforeach
</x-chief-fragments::sidebar-fragment>
