<notifications>
    @if($errors and count($errors) > 0)
        @foreach($errors->all() as $error)
            <notification type="error">
                {!! $error !!}
            </notification>
        @endforeach
    @endif

    @if(count( $_messages = Session::get('messages', [])) > 0)
        @foreach($_messages as $type => $_message)
            <notification type="{{ $type }}">
                {!! $_message !!}
            </notification>
        @endforeach
    @endif
</notifications>
