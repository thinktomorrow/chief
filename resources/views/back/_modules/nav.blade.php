<!-- Start: Sidebar Left -->
<aside id="sidebar_left" class="nano nano-primary affix">

    <!-- Start: Sidebar Left Content -->
    <div class="sidebar-left-content nano-content">

        <!-- Start: Sidebar Header -->
        <header class="sidebar-header">

            <!-- Sidebar Widget - Search (hidden) -->
            <div class="sidebar-widget search-widget hidden">
                <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-search"></i>
              </span>
                    <input type="text" id="sidebar-search" class="form-control" placeholder="Zoeken...">
                </div>
            </div>

        </header>
        <!-- End: Sidebar Header -->

        <!-- Start: Sidebar Left Menu -->
        <ul class="nav sidebar-menu">

            <!-- BUSINESS -->
            <li class="sidebar-label pt20">BUSINESS</li>
            <li>
                <a href="">
                    <span class="glyphicon glyphicon-briefcase"></span>
                    <span class="sidebar-title">Diensten</span>
                </a>
            </li>

            <!-- CONTENT -->
            <li class="sidebar-label pt20">Content</li>
            <li>
                <a href="">
                    <span class="glyphicon glyphicon-bullhorn"></span>
                    <span class="sidebar-title">Blog</span>
                </a>
            </li>
            <li>
                <a href="">
                    <span class="glyphicon glyphicon-calendar"></span>
                    <span class="sidebar-title">Agenda</span>
                </a>
            </li>
            @can('view_users')
                <li>
                    <a href="{{ route('users.index') }}">
                        <span class="glyphicon glyphicon-calendar"></span>
                        <span class="sidebar-title">User management</span>
                    </a>
                </li>
            @endcan
            @can('view_roles')
                <li>
                    <a href="{{ route('roles.index') }}">
                        <span class="glyphicon glyphicon-calendar"></span>
                        <span class="sidebar-title">Role management</span>
                    </a>
                </li>
            @endcan
            @can('view_permissions')
                <li>
                    <a href="{{ route('permissions.index') }}">
                        <span class="glyphicon glyphicon-calendar"></span>
                        <span class="sidebar-title">Permission management</span>
                    </a>
                </li>
            @endcan
        </ul>
        <!-- End: Sidebar Menu -->

    </div>
    <!-- End: Sidebar Left Content -->

</aside>
<!-- End: Sidebar Left -->