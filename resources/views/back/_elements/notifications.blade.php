<notifications>
    @if($errors and count($errors) > 0)
        @foreach($errors->all() as $error)
            <notification
                :description="'{{ $error }}'"
                :type="'error'"
            ></notification>
        @endforeach
    @endif

    @if(count( $_messages = Session::get('messages', [])) > 0)
        @foreach($_messages as $type => $_message)
            <notification
                :description="'{{ strip_tags($_message) }}'"
                :type="'{{ $type }}'"
            ></notification>
        @endforeach
    @endif
</notifications>
