@if (session('alertbarmessage'))
    <div class="border-b border-grey-100 bg-orange-50 p-4">
        <div class="with-inside-link-warning text-center text-orange-500">
            {!! session('alertbarmessage') !!}
        </div>
    </div>
@endif
