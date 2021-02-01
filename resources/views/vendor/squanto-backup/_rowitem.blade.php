<div class="s-column-4 inset-xs">
    <div class="row bg-white border border-grey-100 rounded inset-s">
        <div class="column">
            <a href="{{ route('squanto.edit',$page->id) }}" class="text-grey-500">
                {{ $page->label }}
            </a>

            @if($page->description)
                <p class="text-subtle">{{ $page->description }}</p>
            @endif

            <!-- only show subnav if multiple groupings (other than the default general) -->
            @if((!isset($show_cart_subnav) || $show_cart_subnav) && count($page->groupedlines) > 1)
                <div class="font-s">
                        <?php $id = 1 ?>
                        @foreach($page->groupedlines as $group => $lines)
                            @if($group != 'general')
                                <a class="text-grey-500" href="{{ route('squanto.edit',$page->id) . '#section' . $id++ .'-nl' }}" ><span>{{ $group }}</span>
                                    @if(! $loop->last )
                                    ,
                                    @endif</a>
                            @endif
                        @endforeach
                </div>
            @endif
        </div>

        <div class="column-3 text-right inset-s">
            <a href="{{ route('squanto.edit',$page->id) }}">
                Aanpassen
            </a>
        </div>
    </div>
</div>
