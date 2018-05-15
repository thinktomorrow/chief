<nav class="top-nav bc-white">
    <div class="container">
        <div class="row">
            <ul id="nav-main" class="nav-items">
                <li>
                    <a class="nav-item icon icon-feather" href="{{ route('back.dashboard') }}"></a>
                </li>
                <li><a class="nav-item {{ isActiveUrl('admin/pages*') ? 'active' : '' }}" href="{{ route('back.pages.index') }}">Pages</a></li>
                <li><a class="nav-item {{ isActiveUrl('admin/translations*') ? 'active' : '' }}" href="{{ route('squanto.index') }}">Teksten</a></li>
                <li><a class="nav-item" target="_blank" href="/spirit">Spirit</a></li>
            </ul>

            <div class="column">
                <ul class="nav-items right">
                    <li><a class="nav-item" href="{{ route('back.settings.index') }}"><i class="icon icon-cog"></i></a></li>

                    <li>
                        <dropdown>
                            <span class="nav-item" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">{{ admin()->firstname }}</span>
                            <div v-cloak class="dropdown-box">
                                <a class="block squished-s --link-with-bg" href="{{ route('back.password.edit') }}">Wijzig wachtwoord</a>
                                <a class="block squished-s --link-with-bg" href="{{ route('back.logout') }}">Log out</a>
                            </div>
                        </dropdown>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
