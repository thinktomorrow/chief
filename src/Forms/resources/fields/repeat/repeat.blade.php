@foreach($getRepeatedComponents($locale ?? null) as $components)
    <div data-index="1">
        <span>DELETE</span>
        <span>SORT</span>
        @foreach($components as $childComponent)
            {{ $childComponent }}
        @endforeach
    </div>
@endforeach

<button class="btn btn-primary">+ voeg nieuwe blok toe</button>


@push('custom-scripts-after-vue')

    // Add: API request + refresh??
    // Sort: move indices? => use sortable and base our indices in name on this sortable index

    <script>

        function addRepeatComponent(index) {

            const url = '';


            fetch(url)
                .then((response) => response.text())
                .then((data) => {
                    // Success...
                })
                .catch((error) => {
                    console.error(error);
                });
        }


    </script>

    <template data-repeat-template>
        <div>
            <input type="text" name="foobar[]">
            testje
        </div>
    </template>
@endpush

