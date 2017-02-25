<header class="navbar navbar-fixed-top">
    <div class="navbar-branding">
        <a class="navbar-brand" href="{{ route('admin.home') }}">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Chief">
        </a>
        <span id="toggle_sidemenu_l" class="ad ad-lines"></span>
    </div>


    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle fw600 p15" data-toggle="dropdown"><span class="br64 mr5 glyphicon glyphicon-user"></span> {{ Auth::user()->name }}
                <span class="caret caret-tp hidden-xs"></span>
            </a>
            <ul class="dropdown-menu list-group dropdown-persist w250" role="menu">

                <li class="dropdown-footer">
                    <a href="/logout" class="">
                        <span class="fa fa-power-off pr5"></span> Log uit </a>
                </li>

            </ul>
        </li>
    </ul>

</header>
<!-- End: Header -->