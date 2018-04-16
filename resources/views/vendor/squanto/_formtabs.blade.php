<div class="tab-block">

    <div class="loading" v-show="false">
        loading...
    </div>

    <tabs v-cloak>
        @foreach([
            ['locale' => 'nl', 'name' => 'Nederlands', 'flag' => 'flag-be'],
            ['locale' => 'fr', 'name' => 'Frans', 'flag' => 'flag-fr'],
        ] as $tab)

            @php $tab = (object)$tab; @endphp

            <template slot="tabnav" slot-scope="rows">
                    <nav class="translation-tabs-nav inline-group-s stack-s sticky" style="top:6rem;">
                        <a v-for="tab in rows.tabs"
                           :href="tab.hash"
                           :aria-controls="tab.hash"
                           :aria-selected="tab.isActive"
                           role="tab"
                           class="inline-block squished-s"
                           :class="{'active': tab.isActive }"
                        >
                            <span class="flag" :class="tab.options.flag"></span>
                            <span class="tabs-name" v-if="tab.isActive" v-text="tab.name"></span>
                        </a>
                    </nav>
            </template>

            <tab class="row clearfix gutter" name="{{ $tab->name }}" :options="{ flag: '{{ $tab->flag }}'}">

                <div class="column translation-sidemenu">
                    @if(count($groupedLines) > 2)
                        <ul class="sticky" style="top:9rem;">
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
                                <div id="section{{ $id++ }}-{{ $tab->locale }}" class="section-divider">
                                    <span>{{ $group }}</span>
                                    <span class="divider-locale font-s">{{ $tab->locale }}</span>
                                </div>
                            @endif

                            @foreach($lines as $line)
                                @include('squanto::_form',['locale' => $tab->locale])
                            @endforeach
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
