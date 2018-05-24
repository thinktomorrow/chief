<header class="navbar navbar-fixed-top">
  <div class="navbar-branding">
    <a class="navbar-brand" href="{{ route('chief.back.dashboard') }}">
      <img src="{{ asset('assets/img/logo.svg') }}" alt="Chief">
    </a>
    <span id="toggle_sidemenu_l" class="ad ad-lines"></span>
  </div>


  <ul class="nav navbar-nav navbar-right mn">
    <li>
      <a class="ph15">
        Welkom Johnny!
        {{--Welkom, {{ admin()->firstname }}--}}
      </a>
    </li>
    <li class="menu-divider hidden-xs">
      <i class="fa fa-circle"></i>
    </li>
    <li class="dropdown">
      <a href="#" class="dropdown-toggle ph15" data-toggle="dropdown">
        <span class="glyphicon glyphicon-dashboard"></span> Dashboard
        <span class="caret caret-tp hidden-xs"></span>
      </a>
      <ul class="dropdown-menu pv5 animated animated-short fadeIn" role="menu">
        <li>
          <a href="#">
            <span class="glyphicon glyphicon-barcode mr5"></span> Catalog
          </a>
        </li>
        <li>
          <a href="#">
            <span class="glyphicon glyphicon-shopping-cart mr5"></span> Orders
          </a>
        </li>
        <li>
          <a href="#">
            <span class="glyphicon glyphicon-heart-empty mr5"></span> Marketing
          </a>
        </li>


      </ul>
    </li>
    <li class="menu-divider hidden-xs">
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <span class="glyphicon glyphicon-log-out pr5"></span> Log uit
      </a>
      <form id="logout-form" action="{{ route('chief.back.logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
      </form>
    </li>
  </ul>

</header>
<!-- End: Header -->