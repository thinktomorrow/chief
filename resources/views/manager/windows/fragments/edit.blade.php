<x-chief::sidebar-fragment>
    @foreach($forms->get() as $form)
        {{ $form->render() }}
    @endforeach
</x-chief::sidebar-fragment>
