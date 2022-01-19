<x-chief::sidebar-fragment>
    @foreach($forms->get() as $form)
        {{ $form->render() }}
        <input form="{{ $form->getId() }}" type="hidden" name="order" value="{{ $order ?? 0 }}">
    @endforeach
</x-chief::sidebar-fragment>

@include('chief::components.file-component')
@include('chief::components.filesupload-component')
