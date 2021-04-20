<input type="hidden" name="menu_type" value="{{ $menuitem->menu_type }}">

<div class="space-y-12">
    @include('chief::admin.menu._formgroups.label')
    @include('chief::admin.menu._formgroups.link')

    @if(count($parents) > 0)
        @include('chief::admin.menu._formgroups.parent')
    @endif

    @if($menuitem->id && ! $menuitem->siblings()->isEmpty())
        @include('chief::admin.menu._formgroups.order')
    @endif
</div>
