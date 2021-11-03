<div class="-my-6 divide-y divide-grey-100">
    <div class="py-6">
        @include('chief::admin.menu._formgroups.label')
    </div>

    <div class="py-6">
        @include('chief::admin.menu._formgroups.link')
    </div>

    @if(count($parents) > 0)
        <div class="py-6">
            @include('chief::admin.menu._formgroups.parent')
        </div>
    @endif

    @if($menuitem->id && ! $menuitem->siblings()->isEmpty())
        <div class="py-6">
            @include('chief::admin.menu._formgroups.order')
        </div>
    @endif
</div>
