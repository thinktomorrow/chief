 <div class="col-md-9">
    <div class="tab-block mb25">
        <ul class="nav nav-tabs nav-justified tabs-border">
	        @if($article->hasMultipleApplicationLocales())
	            @foreach($article->getAvailableLocales() as $locale)
	                <li class="{{ 'nl'==$locale?'active':'' }}">
	                    <a href="#tab_{{  $locale }}" data-toggle="tab">{{ ucfirst($locale) }}</a>
	                </li>
	            @endforeach
			@endif
        </ul>

        <div class="tab-content pn">
            @foreach($article->getAvailableLocales() as $locale)
                <div id="tab_{{ $locale }}" class="tab-pane{{ 'nl'==$locale?' active':'' }}">
                    @include('back.articles.form.form',['locale' => $locale])
                </div>
            @endforeach
            <div class="clearfix"></div>
        </div>
        <!--end tab content -->
    </div>
    <!-- end tab block -->
</div><!-- end first column -->
