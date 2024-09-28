@if ($this->hasPagination() && $this->resultTotal > $this->resultPageCount)
    <div class="px-4 py-3">
        {{ $results->onEachSide(0)->links() }}
    </div>
@endif
