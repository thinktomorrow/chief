<input type="hidden" name="menu_type" value="{{ $menuitem->menu_type }}">

<div class="divide-y divide-grey-100 -m-12">
    <div class="p-12">
        @include('chief::admin.menu._formgroups.label')
    </div>

    <div class="p-12">
        @include('chief::admin.menu._formgroups.link')
    </div>

    @if(count($parents) > 0)
        <div class="p-12">
            @include('chief::admin.menu._formgroups.parent')
        </div>
    @endif

    @if($menuitem->id && ! $menuitem->siblings()->isEmpty())
        <div class="p-12">
            @include('chief::admin.menu._formgroups.order')
        </div>
    @endif
</div>
