@if(session('alertbarmessage'))
    <div class="shadow-sm bg-orange-50">
        <div class="container-sm">
            <div class="row">
                <div class="w-full">
                    <div class="py-4 font-medium text-center text-orange-500 with-inside-link-warning">
                        <x-chief-icon-label icon="home" space="large">{!! session('alertbarmessage') !!}</x-chief-icon-label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
