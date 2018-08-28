<div class="column-3">
    <div class="panel panel-default bg-white">
        <div class="inset-s">
            <a href="{{ route('squanto.edit',$page->id) }}">
                {{ $page->label }}
            </a>

            @if($page->description)
                <p class="text-subtle">{{ $page->description }}</p>
            @endif

            <!-- only show subnav if multiple groupings (other than the default general) -->
            @if((!isset($show_cart_subnav) || $show_cart_subnav) && count($page->groupedlines) > 1)
                <div class="row card-body inset-s">
                    <ul>
                        <?php $id = 1 ?>
                        @foreach($page->groupedlines as $group => $lines)
                            @if($group != 'general')
                                <li>
                                    <a href="{{ route('squanto.edit',$page->id) . '#section' . $id++ .'-nl' }}" ><span>{{ $group }}</span></a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <hr style="margin:0;">

        <div class="inset-s font-s">

            <a href="{{ route('squanto.edit',$page->id) }}">
                Bewerken
            </a>
        </div>
    </div>
</div>
