<x-chief::sidebar-fragment>
    @foreach($forms->get() as $form)
        {{ $form->tag('fragments')->editInline()->showAsBlank()->render() }}
    @endforeach
</x-chief::sidebar-fragment>

@include('chief::components.file-component')
@include('chief::components.filesupload-component')
