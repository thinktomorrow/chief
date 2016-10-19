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

            <li class="sidebar-label pt20">SUPPORT</li>
            <li>
                <a href="{{ route('back.contacts.index') }}">
                    <span class="glyphicon glyphicon-inbox"></span>
                    <span class="sidebar-title">Contacten
                        @if(($count = \Hura\Contacts\Contact::countUnread()) && $count > 0)
                            <span id="contact-badge" class="badge badge-info" title="{{ $count.' ongelezen' }}">{{ $count }}</span>
                        @endif
                    </span>
                </a>
            </li>

            <!-- HURA -->
            <li class="sidebar-label pt20">Hura</li>
            <li>
                <a href="{{ route('back.services.index') }}">
                    <span class="glyphicon glyphicon-briefcase"></span>
                    <span class="sidebar-title">Diensten</span>
                </a>
            </li>
            <li>
                <a href="{{ route('back.consultants.index') }}">
                    <span class="glyphicon glyphicon-user"></span>
                    <span class="sidebar-title">Consulenten</span>
                </a>
            </li>
            <li>
                <a href="{{ route('back.testimonials.index') }}">
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="sidebar-title">Testimonials</span>
                </a>
            </li>

            <!-- CONTENT -->
            <li class="sidebar-label pt20">Content</li>
            <li>
                <a href="{{ route('back.articles.index') }}">
                    <span class="glyphicon glyphicon-bullhorn"></span>
                    <span class="sidebar-title">Nieuws</span>
                </a>
            </li>
            <li>
                <a href="{{ route('back.events.index') }}">
                    <span class="glyphicon glyphicon-calendar"></span>
                    <span class="sidebar-title">Agenda</span>
                </a>
            </li>
            <li>
                <a href="{{ route('back.banners.index') }}">
                    <span class="glyphicon glyphicon-calendar"></span>
                    <span class="sidebar-title">Banner</span>
                </a>
            </li>
            <li>
                <a href="{{ route('back.clients.index') }}">
                    <span class="glyphicon glyphicon-user"></span>
                    <span class="sidebar-title">Klanten</span>
                </a>
            </li>
            <li>
                <a href="{{ route('back.pages.index') }}">
                    <span class="glyphicon glyphicon-file"></span>
                    <span class="sidebar-title">Pagina's</span>
                </a>
            </li>
	        <li>
		        <a href="{{ route('back.partners.index') }}">
			        <span class="glyphicon glyphicon-user"></span>
			        <span class="sidebar-title">Partners</span>
		        </a>
	        </li>
	        <li>
		        <a href="{{ route('back.documents.index') }}">
			        <span class="glyphicon glyphicon-file"></span>
			        <span class="sidebar-title">Documents</span>
		        </a>
	        </li>
            <li>
                <a href="{{ route('back.squanto.index') }}">
                    <span class="glyphicon glyphicon-file"></span>
                    <span class="sidebar-title">Translations</span>
                </a>
            </li>

        </ul>
        <!-- End: Sidebar Menu -->

    </div>
    <!-- End: Sidebar Left Content -->

</aside>
<!-- End: Sidebar Left -->