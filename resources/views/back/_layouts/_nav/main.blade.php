<nav class="top-nav bc-white">
    <div class="container">
        <div class="row">
            <ul id="nav-main" class="nav-items">
                <li>
                    <a class="nav-item icon icon-feather" href="{{ route('back.dashboard') }}"></a>
                </li>
                <li><a class="nav-item {{ isActiveUrl('admin/pages*') ? 'active' : '' }}" href="{{ route('back.pages.index') }}">Artikels</a></li>
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
                                <a class="block squished-s --link-with-bg" href="{{ route('back.logout') }}"><i class="icon-delete inline-s"></i> Log out</a>
                            </div>
                        </dropdown>

                        {{--<div class="btn-group">--}}
                            {{--<button @click="showModal('publication-now-page')" type="button" class="btn btn-primary">Wijzigingen opslaan--}}
                            {{--</button><button-dropdown class="inline-block btn-group-last" btn_name="<i class='icon icon-chevron-down'></i>">--}}
                                {{--<div v-cloak>--}}
                                    {{--<a href="#" class="block squished-s --link-with-bg">Haal pagina offline</a>--}}
                                    {{--<a href="#" class="block squished-s --link-with-bg" @click="showModal('delete-page')">Verwijder pagina</a>--}}
                                {{--</div>--}}
                            {{--</button-dropdown>--}}
                        {{--</div>--}}


                        {{--<div class="dropdown">--}}
                            {{--<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle ">--}}
                                {{--<span>{{ admin()->firstname }}</span>--}}
                                {{--<div class="dropdown-menu">--}}
                                    {{--<a href="{{ route('back.logout') }}"><i class="icon-delete squished"></i> Log out</a>--}}
                                {{--</div>--}}
                            {{--</button>--}}
                        {{--</div>--}}
                        {{-- <a class="nav-item dropdown-toggle">
                            <span>{{ admin()->firstname }}</span>
                        </a>
                        <div class="dropdown-menu">
                            <!-- ITEMS -->
                            <ul>
                                <li>
                                    <a href="{{ route('back.logout') }}"><i class="icon-delete squished"></i> Log out</a>
                                </li>
                            </ul>
                        </div> --}}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
