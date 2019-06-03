@if(count( $_messages = Session::get('messages', [])) > 0)
    @foreach($_messages as $type => $_message)
        <alert class="alert --raised inset-s fixed right-0 bottom-0 m-2 animated animation-delayed-4 fadeOutDown" type="{{$type}}">
            <div v-cloak>
                {!! $_message !!}
            </div>
        </alert>
    @endforeach
@endif