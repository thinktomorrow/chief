<li>
    <a class="nav-item icon icon-feather" href="{{ route('chief.back.dashboard') }}"></a>
</li>

<li>
    <dropdown>
        <span class="center-y nav-item {{ (isActiveUrl('admin/managers*')) ? 'active' : '' }}" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">Collecties</span>
        <div v-cloak class="dropdown-box inset-s">
            @foreach(app(\Thinktomorrow\Chief\Management\Register::class)->all() as $registration)

                <?php $manager = app(\Thinktomorrow\Chief\Management\Managers::class)->findByKey($registration->key()); ?>

                <a class="block squished --link-with-bg {{ isActiveUrl('admin/managers/'.$registration->key().'*') ? 'active' : '' }}" href="{{ route('chief.back.managers.index',['key' => $registration->key()]) }}">
                    {{ $manager->managerDetails()->plural }}
                </a>

            @endforeach
        </div>
    </dropdown>
</li>
<li>
    <dropdown>
        <span class="center-y nav-item {{ (isActiveUrl('admin/translations*') || isActiveUrl('admin/menu*')) ? 'active' : '' }}" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">Site</span>
        <div v-cloak class="dropdown-box inset-s">
            <a class="block squished --link-with-bg {{ isActiveUrl('admin/menus*') ? 'active' : '' }}" href="{{ route('chief.back.menus.index') }}">Menu</a>
            <a class="block squished --link-with-bg {{ isActiveUrl('admin/modules*') ? 'active' : '' }}" href="{{ route('chief.back.modules.index') }}">Vaste modules</a>
            <a class="block squished --link-with-bg {{ isActiveUrl('admin/translations*') ? 'active' : '' }}" href="{{ route('squanto.index') }}">Teksten</a>
        </div>
    </dropdown>
</li>