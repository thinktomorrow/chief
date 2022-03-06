<div>
    @if($model->adminConfig()->getBreadCrumb())
        <a href="{{ visitedUrl($model->adminConfig()->getBreadCrumb()->url) }}" class="link link-primary">
            <x-chief-icon-label type="back">{{ $model->adminConfig()->getBreadCrumb()->label }}</x-chief-icon-label>
        </a>
    @else
        @adminCan('index')
            <a href="{{ visitedUrl($manager->route('index')) }}" class="link link-primary">
                <x-chief-icon-label type="back">Terug naar overzicht</x-chief-icon-label>
            </a>
        @endAdminCan
    @endif
</div>
