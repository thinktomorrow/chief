<div class="column-3">
    <div class="card --online">
        <div class="row card-header center-y inset-s">
            <div class="column">
                <a href="{{ route('squanto.edit',$page->id) }}">
                    {{ $page->label }}
                </a>
            </div>
            <div class="column card-menu text-right">
                <a title="Edit {{ $page->label }}" href="{{ route('squanto.edit',$page->id) }}"><span class="icon-edit"></span> </a>
            </div>
        </div>

        @if(!isset($show_cart_subnav) || $show_cart_subnav)
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
</div>
