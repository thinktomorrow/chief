@can('update-you')
    <x-chief::nav.item 
        label="{{ ucfirst(chiefAdmin()->firstname) }}" 
        url="{{ route('chief.back.you.edit') }}"
        icon="<svg><use xlink:href='#icon-user'></use></svg>"
        collapsible 
    />
@endcan

<x-chief::nav.item 
    label="Logout" 
    url="{{ route('chief.back.logout') }}"
    icon="<svg><use xlink:href='#icon-logout'></use></svg>"
    collapsible 
/>

