<x-chief-fragments::sidebar>
    @foreach($forms->get() as $form)
        {{ $form->tag('fragments')->editInline()->showAsBlank()->render() }}
    @endforeach
</x-chief-fragments::sidebar>
