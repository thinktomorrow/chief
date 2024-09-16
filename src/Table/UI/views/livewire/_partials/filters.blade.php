<div class="flex items-start gap-2">
    @foreach ($this->getFilters() as $filter)
        {!! $filter->render() !!}
    @endforeach
</div>
