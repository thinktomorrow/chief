@if($forms->has($id))
    {{ $forms->find($id)->render() }}
@endif
