<notifications>
    @if($errors and count($errors) > 0)
        @foreach($errors->all() as $error)
            <notification
                :title="'Error'"
                :description="'{{ $error }}'"
                :type="'error'"
            ></notification>
        @endforeach
    @endif

    @if(count( $_messages = Session::get('messages', [])) > 0)
        @foreach($_messages as $type => $_message)
            <notification
                :title="'Information'"
                :description="'{!! $_message !!}'"
                :type="'{{ $type }}'"
            ></notification>
        @endforeach
    @endif
</notifications>
