@if(session('alertbarmessage'))
    <section class="bg-warning text-secondary-800">
        <div class="container squished text-center">
            <svg width="18" height="18" class="inline-block fill-current"><use xlink:href="#home"/></svg>
            {!! session('alertbarmessage') !!}
        </div>
    </section>
@endif
