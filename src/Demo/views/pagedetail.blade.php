@if(count( $_messages = Session::get('messages', [])) > 0)
    @foreach($_messages as $type => $_message)
        {!! $_message !!}
    @endforeach
@endif

<div>
    {{ $page->title }}
</div>
