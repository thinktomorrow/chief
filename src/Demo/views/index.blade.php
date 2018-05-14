@if(count( $_messages = Session::get('messages', [])) > 0)
    @foreach($_messages as $type => $_message)
        {!! $_message !!}
    @endforeach
@endif
<h1>PAGES</h1>

<ul>
    @foreach($pages as $page)
        <li><a href="{{ route('demo.pages.show', $page->slug) }}">{{ $page->title }}</a></li>
    @endforeach
</ul>