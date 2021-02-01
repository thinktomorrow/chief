@if(count( $_messages = Session::get('messages')) > 0)
    <div class="alerts alert-remove">
        @foreach($_messages as $type => $_message)
            <div class="alert alert-{{$type}} alert-dismissable">{!! $_message !!}</div>
        @endforeach
    </div>
@endif