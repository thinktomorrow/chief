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
                    <input type="text" id="sidebar-search" class="form-control" placeholder="Search...">
                </div>
            </div>

        </header>
        <!-- End: Sidebar Header -->

        <!-- Start: Sidebar Left Menu -->
        <ul class="nav sidebar-menu">

            <!-- sidebar resources -->
            <li class="sidebar-label pt20">Daily</li>
            <li>
                <a class="accordion-toggle" href="#">
                    <span class="glyphicon glyphicon-list-alt"></span>
                    <span class="sidebar-title">Products</span>
                    <span class="caret"></span>
                </a>
                <ul class="nav sub-nav">
                    <li><a href="#">overview</a></li>
                    <li><a href="#">sorting</a></li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <span class="glyphicon glyphicon-comment"></span>
                    <span class="sidebar-title">Second page</span>
                </a>
            </li>

            <li class="sidebar-label pt20">Monthly</li>
            <li>
                <a href="#">
                    <span class="glyphicon glyphicon-briefcase"></span>
                    <span class="sidebar-title">Jobs</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    <span class="sidebar-title">FAQ</span>
                </a>
            </li>
            <li>
                <a class="accordion-toggle" href="#">
                    <span class="glyphicon glyphicon-file"></span>
                    <span class="sidebar-title">Pages</span>
                    <span class="caret"></span>
                </a>
                <ul class="nav sub-nav">
                    @foreach(\Chief\Trans\Transgroup::getAll() as $group)
                        <li><a href="{{ route('admin.trans.edit',$group->slug) }}">{{ $group->label }}</a></li>
                    @endforeach
                </ul>
            </li>

            <li class="sidebar-label pt20">SUPPORT</li>
            <li>
                <a href="{{ route('admin.contacts.index') }}">
                    <span class="glyphicon glyphicon-inbox"></span>
                    <span class="sidebar-title">Contacts</span>
                </a>
            </li>
	        <li>
                <a href="{{ route('admin.typeform.index') }}">
                    <span class="glyphicon glyphicon-inbox"></span>
                    <span class="sidebar-title">Form submits</span>
	                @if(app(\BNP\Typeform\NewEntryCount::class)->getCount()!= 0)
	                    <span class="label label-xs bg-primary">{{app(\BNP\Typeform\NewEntryCount::class)->getCount()}} New</span>
                    @endif
                </a>
            </li>

        </ul>
        <!-- End: Sidebar Menu -->

    </div>
    <!-- End: Sidebar Left Content -->

</aside>
<!-- End: Sidebar Left -->