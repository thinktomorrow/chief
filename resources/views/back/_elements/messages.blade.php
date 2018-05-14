@if(count( $_messages = Session::get('messages', [])) > 0)
    @foreach($_messages as $type => $_message)
        <mkiha-alert type="{{$type}}" body='{!! $_message !!}'></mkiha-alert>
    @endforeach
@endif