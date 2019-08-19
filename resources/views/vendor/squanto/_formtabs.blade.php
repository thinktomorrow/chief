<div class="tab-block">

    <div class="loading" v-show="false">
        loading...
    </div>
    @php
        $locales = config('translatable.locales');
        foreach($locales as $locale){
            $tabs[] = [
                'locale'   => $locale,
                'name'     => $locale,
            ];
        }
    @endphp
    <tabs v-cloak>
        @foreach($tabs as $tab)

            @php $tab = (object)$tab; @endphp

            <template slot="tabnav" slot-scope="rows">
                    <nav class="flex w-full border-b border-grey-200 mb-6 sticky top-0">
                        <a v-for="tab in rows.tabs"
                           :href="tab.hash"
                           :aria-controls="tab.hash"
                           :aria-selected="tab.isActive"
                           role="tab"
                           class="block squished --bottomline"
                           :class="{'active': tab.isActive }"
                        >
                            <span class="tabs-name" v-text="tab.name"></span>
                        </a>
                    </nav>
            </template>

            <tab class="row clearfix gutter" name="{{ $tab->name }}">

                <div class="column translation-sidemenu">
                    @if(count($groupedLines) > 2)
                        <ul class="sticky">
                            <?php $id = 1 ?>
                            @foreach($groupedLines as $group => $lines)
                                @if($group != 'general')
                                    <li>
                                        <a class="anchor-item squished" href="#section{{ $id++ }}-{{ $tab->locale }}" >{{ $group }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="column-9">
                    <div class="tab-content">
                        <?php $id = 1 ?>
                        @foreach($groupedLines as $group => $lines)

                            @if($group != 'general')
                                <div id="section{{ $id++ }}-{{ $tab->locale }}" class="section-divider ">
                                    <span>{{ $group }}</span>
                                    <span class="divider-locale font-s">{{ $tab->locale }}</span>
                                </div>
                            @endif
                            <div class="bg-white border border-grey-100 rounded inset-s z-10 relative">
                            @foreach($lines as $line)
                                @include('squanto::_form',['locale' => $tab->locale])
                            @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </tab>
        @endforeach
    </tabs>
    <!--end tab content -->
</div>
<!-- end tab block -->

@push('custom-scripts-after-vue')
    <script>

    $(document).ready(function(){
        // go to the location chose from the index on page load
        if(window.location.hash) {
            // get the anchor en scroll to the location
            var currentHash = window.location.hash;

            anchorScroll( $(currentHash), $(currentHash), 200 );
        }

        // clicking on a manu item scrolls to the correct position
        $(".anchor-item").click(function(e) {
            e.preventDefault();

            // add active state on selected item
            $(".anchor-item").removeClass('active');
            $(this).addClass('active');

            // call anchor scroll to position
            anchorScroll( $(this), $($(this).attr("href")), 200 );
        });
    });

    function anchorScroll(selectedItem, targetItem, speed) {
        // get the offsets of the items to position it according to the window
        var selectedOffset = selectedItem.offset();
        var targetOffset = targetItem.offset();

        // calculate the difference in offset
        var offsetCalc = Math.abs(targetOffset.top - selectedOffset.top);

        // determine the speed how fast you want to scroll to the position
        // (depends on how long scrollign distance is)
        var speed = (offsetCalc * speed) / 1000;

        // animate the scroll to the position depending on location
        $("html,body").animate({
            scrollTop: targetOffset.top - 135
        }, speed);

        // clear the location hash
        location.hash = '';
    }
    </script>
@endpush
