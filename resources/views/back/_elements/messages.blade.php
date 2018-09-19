@if(count( $_messages = Session::get('messages', [])) > 0)
    @foreach($_messages as $type => $_message)
        <alert class="alert --raised fixed--bottom-right inset-s animated animation-delayed-4 fadeOutDown" type="{{$type}}">
            <div v-cloak>
                {!! $_message !!}
            </div>
        </alert>
    @endforeach
@endif