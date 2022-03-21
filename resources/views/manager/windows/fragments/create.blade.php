<x-chief::sidebar-fragment>
    @foreach($forms->get() as $form)
        {{ $form->editInline()->showAsBlank()->render() }}
    @endforeach
</x-chief::sidebar-fragment>

@include('chief::components.file-component')
@include('chief::components.filesupload-component')
