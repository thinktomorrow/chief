<div>
    @if($breadCrumb = $resource->getPageBreadCrumb())
        <a href="{{ visitedUrl($breadCrumb->url) }}" class="link link-primary">
            <x-chief::icon-label type="back">{{ $breadCrumb->label }}</x-chief::icon-label>
        </a>
    @else
        @adminCan('index')
            <a href="{{ visitedUrl($manager->route('index')) }}" class="link link-primary">
                <x-chief::icon-label type="back">Overzicht</x-chief::icon-label>
            </a>
        @endAdminCan
    @endif
</div>
