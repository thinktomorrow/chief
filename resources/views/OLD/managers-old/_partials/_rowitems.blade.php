<!-- default admin index -->
@foreach($managers as $manager)
    @include('chief::back.managers._partials._rowitem')
@endforeach
