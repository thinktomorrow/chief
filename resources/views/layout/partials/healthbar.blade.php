@if(session('alertbarmessage'))
    <div class="bg-orange-50">
        <div class="container-sm">
            <div class="row">
                <div class="w-full">
                    <div class="py-4 font-medium text-center text-orange-500 with-inside-link-warning">
                        <x-icon-label icon="home" space="large">{!! session('alertbarmessage') !!}</x-icon-label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
