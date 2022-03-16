@if(session('alertbarmessage'))
    <div class="p-4 border-b border-grey-100 bg-orange-50">
        <div class="text-center text-orange-500 with-inside-link-warning">
            <x-chief-icon-label icon="home" space="large">
                {!! session('alertbarmessage') !!}
            </x-chief-icon-label>
        </div>
    </div>
@endif
