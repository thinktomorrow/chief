<?php

if(!function_exists('noSectionSelected')){
    function noSectionSelected(){
        if( isActiveUrl('admin/articles*')) return false;
        if( isActiveUrl('admin/translations*')) return false;
        return true;
    }
}


?>

<nav class="top-nav bc-white">
    <div class="container">
        <div class="row">
            <ul id="nav-main" class="nav-items {{ noSectionSelected() ? '' : '--hidden' }}">
                <li>
                    <a class="nav-item icon icon-feather text-subtle" href="{{ route('back.dashboard') }}"></a>
                </li>
                <li><a class="nav-item" data-nav="nav-articles" href="#">Artikels</a></li>
                <li><a class="nav-item" data-nav="nav-translations" href="#">Site</a></li>
            </ul>

            <ul id="nav-articles" class="nav-items {{ isActiveUrl('admin/articles*') || isActiveUrl('admin/stock*') ? '' : '--hidden' }}">
                <li><a class="nav-item icon icon-chevron-up" data-nav="nav-main" href="#"></a></li>
                <li><a class="nav-item {{ isActiveUrl('admin/articles*') ? 'active' : '' }}" href="{{ route('back.articles.index') }}">Artikels</a></li>
            </ul>

            <ul id="nav-translations" class="nav-items {{ isActiveUrl('admin/translations*') ? '' : '--hidden' }}">
                <li><a class="nav-item icon icon-chevron-up" data-nav="nav-main" href="#"></a></li>
                <li><a class="nav-item {{ isActiveUrl('admin/translations*') ? 'active' : '' }}" href="{{ route('squanto.index') }}">Teksten</a></li>
            </ul>

            <div class="column">
                <ul class="nav-items right">
                    <li><a class="nav-item" href="{{ route('back.settings.index') }}"><i class="icon icon-cog"></i></a></li>

                    <li class="dropdown nav-dropdown">
                        <a class="nav-item dropdown-toggle">
                            <span>Johnny</span>
                            {{--<span>{{ admin()->firstname }}</span>--}}
                        </a>
                        <div class="dropdown--content inset-s">
                            <!-- ITEMS -->
                            <ul>
                                <li>
                                    <a href="{{ route('back.logout') }}"><i class="icon-delete squished"></i> Log out</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>


        </div>
    </div>

</nav>
@push('custom-scripts')
<script>
    var navs = [
            document.getElementById('nav-main'),
            document.getElementById('nav-articles'),
            document.getElementById('nav-translations'),
        ],
        navItems = document.querySelectorAll('[data-nav]');

    function hideNavs()
    {
        for(var i=0; i < navs.length; i++) {
            navs[i].classList.add('--hidden');
        }
    }

    function showNav(e)
    {
        e.preventDefault();

        var navId = e.target.getAttribute('data-nav'),
            nav = document.getElementById(navId);

        hideNavs();
        nav.classList.remove('--hidden');
        return false;
    }

    for(var i=0; i < navItems.length; i++) {
        navItems[i].addEventListener('click', showNav, false);
    }

</script>
@endpush
