@extends('chief::back._layouts.master')

@section('content')
<section class="container">
    <div id="fragmentContainer"></div>

    <h3>Fragments</h3>
    <div class="row">
        @foreach($fragments as $fragment)
            @include('chief::manager.cards.fragments.component._card', [
                'model' => $fragment['model'],
                'owner' => $model,
                'manager' => $fragment['manager'],
                'loop' => $loop,
            ])
        @endforeach
    </div>
    <div class="mt-8">
            @forelse($allowedFragments as $allowedFragment)
                <a class="btn btn-primary" href="{{ $allowedFragment['manager']->route('fragment-create', $model) }}">
                    Voeg een {{ $allowedFragment['model']->adminLabel('label') }} toe
                </a>

                <!-- // Any shared available? -> selectable
                // Any static model available? -> selectable

            // EDIT A FRAGMENT
                // is managed model?
                    // has edit
                        // open edit form in sidebar
                        // after edit close sidebar and update fragments...
                // static or shared model
                    // any fragment options to be managed in sidebar -->
            @empty
                No available fragments.
            @endforelse
    </div>
</section>

@stop


@push('custom-scripts-after-vue')

    <script>

        function listenFetchLinks()
        {
            var els = document.querySelectorAll('[data-fetch]');

            Array.from(els).forEach(function(el){
                el.addEventListener('click', function(event){
                    event.preventDefault();
                    fetchLink(this.getAttribute('href'), document.getElementById('fragmentContainer'));
                });
            });
        }

        function fetchLink(url, container)
        {
            fetch(url).then(
                response => { return response.text()}
            ).then(data => {
                container.innerHTML = data;
                listenFetchLinks();
            });
        }

        listenFetchLinks();

    </script>
@endpush
