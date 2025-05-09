THIS IS ARTICLE PAGE VIEW
@foreach(getFragments() as $fragment)
    {{ $fragment->render() }}
@endforeach
