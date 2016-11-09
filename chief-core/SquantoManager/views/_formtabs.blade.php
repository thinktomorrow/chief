<div class="col-md-9">
    <div class="tab-block mb25">
        <ul class="nav tabs-left tabs-border tabbed-nav">
            @foreach($available_locales as $locale)
                <li class="{{ 'nl'==$locale?'active':'' }}">
                    <a href="#tab_{{  $locale }}" data-toggle="tab">{{ ucfirst($locale) }}</a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content">
            @foreach($available_locales as $locale)
                <div id="tab_{{ $locale }}" class="tab-pane{{ 'nl'==$locale?' active':'' }}">
                    @foreach($groupedLines as $group => $lines)

                        @if($group != 'general')
                            <div class="section-divider mb40 mt40">
                                <span>{{ $group }}</span>
                            </div>
                        @endif

                        @foreach($lines as $line)
                            @include('squanto::_form',['locale' => $locale])
                        @endforeach

                    @endforeach
                </div>
            @endforeach
            <div class="clearfix"></div>
        </div>
        <!--end tab content -->
    </div>
    <!-- end tab block -->
</div><!-- end first column -->
