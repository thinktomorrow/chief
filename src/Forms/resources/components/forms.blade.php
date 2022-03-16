@props([
    'id' => null,
    'position' => null,
])

@if($id && $forms->has($id))
    {{ $forms->find($id)->render() }}
@endif

@if($position)
    @foreach($forms->filterByPosition($position)->get() as $form)
        {{ $form->render() }}
    @endforeach
@endif
