<nav class="top-nav bc-white">
    <div class="container">
        <div class="row">
            <ul id="nav-main" class="nav-items">
                <li>
                    <a class="nav-item icon icon-feather text-subtle" href="{{ route('back.dashboard') }}"></a>
                </li>
                <li><a class="nav-item {{ isActiveUrl('admin/articles*') ? 'active' : '' }}" href="{{ route('back.articles.index') }}">Artikels</a></li>
                <li><a class="nav-item {{ isActiveUrl('admin/translations*') ? 'active' : '' }}" href="{{ route('squanto.index') }}">Teksten</a></li>
                <li><a class="nav-item" target="_blank" href="/spirit">Spirit</a></li>
            </ul>

            <div class="column">
                <ul class="nav-items right">
                    <li><a class="nav-item" href="{{ route('back.settings.index') }}"><i class="icon icon-cog"></i></a></li>

                    <li class="dropdown nav-dropdown">
                        <a class="nav-item dropdown-toggle">
                            <span>{{ admin()->firstname }}</span>
                        </a>
                        {{--<div class="dropdown--content inset-s">--}}
                        {{--<!-- ITEMS -->--}}
                        {{--<ul>--}}
                        {{--<li>--}}
                        {{--<a href="{{ route('back.logout') }}"><i class="icon-delete squished"></i> Log out</a>--}}
                        {{--</li>--}}
                        {{--</ul>--}}
                        {{--</div>--}}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
