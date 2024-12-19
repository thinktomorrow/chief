<div>
    @if($breadCrumb = $resource->getPageBreadCrumb())
        <a href="{{ visitedUrl($breadCrumb->url) }}" class="link link-primary">
            <x-chief::icon-label type="back">{{ $breadCrumb->label }}</x-chief::icon-label>
        </a>
    @endif
</div>
