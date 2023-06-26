@can('update-you')
    <x-chief::nav.item
        label="{{ ucfirst(chiefAdmin()->firstname) }}"
        url="{{ route('chief.back.you.edit') }}"
        icon="<svg><use xlink:href='#icon-user'></use></svg>"
        collapsible
    />
@endcan
