<div class="my-12">
    <div class="container space-y-2">
        <div class="row">
            <div class="w-full">
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-link-label type="back">Ga terug</x-link-label>
                </a>
            </div>
        </div>

        <div class="row-between-start">
            <div class="w-3/4">
                <h1 class="text-grey-900">
                    {{ ucfirst($model->adminLabel('page_title')) }}
                </h1>
            </div>

            <div class="w-1/4 flex justify-end items-center">
                @adminCan('create')
                    <a href="@adminRoute('create')" class="btn btn-primary">
                        <x-link-label type="add">Voeg een @adminLabel('label') toe</x-link-label>
                    </a>
                @endAdminCan
            </div>
        </div>
    </div>
</div>
