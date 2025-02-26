THIS IS ROOT FRAGMENT
@foreach($fragment->getFragments() as $child)
    {{ $child->render() }}
@endforeach
