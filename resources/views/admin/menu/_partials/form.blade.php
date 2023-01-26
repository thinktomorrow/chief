<div class="space-y-6">
    @include('chief::admin.menu._formgroups.label')
    @include('chief::admin.menu._formgroups.link')
    @includeWhen(count($parents) > 0, 'chief::admin.menu._formgroups.parent')
    @includeWhen($menuitem->id && ! $menuitem->siblings()->isEmpty(), 'chief::admin.menu._formgroups.order')
</div>
