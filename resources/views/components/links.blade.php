<div data-links-component>
    @component('chief::components.card', [
        'title' => 'Visibiliteit',
        'edit_request_url' => $manager->route('links-edit', $model),
        'type' => 'links'
    ])
        <div class="flex items-center space-x-2">
            <span class="font-medium">Status:</span>

            <span class="font-medium text-success">Online</span>
        </div>

        <div class="space-y-1">
            @unless($linkForm->exist())
                <p>Geen huidige links</p>
            @else
                @foreach($linkForm->links() as $locale => $links)
                    @if($links->current)
                        <div class="flex items-center space-x-3">
                            <span class="bg-grey-150 font-medium text-grey-900 py-1 px-2 rounded-lg">{{ $locale }}</span>

                            <a class="link link-primary" target="_blank" rel="noopener" href="{{ $links->url }}">
                                <x-link-label type="external-link" position="append">{{ $links->current->slug }}</x-link-label>
                            </a>
                        </div>
                    @endif
                @endforeach
            @endunless
        </div>

        {{-- @adminCan('preview', $model)
            <a class="block" href="@adminRoute('preview', $model)" target="_blank">Bekijk op site</a>
        @endAdminCan

        @foreach(['draft', 'publish', 'unpublish', 'archive', 'unarchive'] as $action)
            @adminCan($action, $model)
                @include('chief::back.managers._transitions.' . $action)
            @endAdminCan
        @endforeach --}}


        {{-- @adminCan('delete', $model)
            @include('chief::back.managers._transitions.delete')
        @endAdminCan --}}
    @endcomponent
</div>

{{-- <div data-links-component class="flex flex-col space-y-6">
    <div>
        <h3>Links</h3>
        @unless($linkForm->exist())
            <p> Geen huidige links </p>
        @else
            @foreach($linkForm->links() as $locale => $links)
                @if($links->current)
                    <span>{{ $locale }}: <a target="_blank" rel="noopener" href="{{ $links->url }}">{{ $links->current->slug }}</a></span>
                @endif
            @endforeach
        @endunless
    </div>

    <div>
        <a
            data-sidebar-links-edit
            class="link link-primary"
            href="@adminRoute('links-edit', $model)"
        >
            <x-link-label type="edit">Aanpassen</x-link-label>
        </a>
    </div>
</div> --}}
