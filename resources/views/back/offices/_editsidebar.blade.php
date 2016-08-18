<div class="form-group">
    <label class="control-label" for="inputOffice">Office logo:</label>
    <div class="bs-component">
        @include('admin.offices._fileupload')
    </div>
</div>

<div class="form-group">
    <label class="control-label" for="inputOffice">Position on homepage:</label>
    <i class="fa fa-question-circle" data-toggle="tooltip" title="Drag and drop your office to its new position."></i>
    <div class="bs-component">
        <ul class="list-group sortable">

            @foreach(\BNP\Offices\Office::getAll() as $sibling)

                <?php $current = ($sibling->id === $office->id) ? ' current' : null; ?>

                <li class="list-group-item{{$current}}">
                    <input type="hidden" name="sequence[]" value="{{ $sibling->id }}">
                    <span title="{{ $sibling->title }}">{{ $sibling->country_key.' '.$sibling->title }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<hr>

<div class="form-group">
    <div class="bs-component text-center">
        <button class="btn btn-success btn-lg" type="submit"><i class="fa fa-check"></i> Save your changes</button>
    </div>
    <div class="text-center">
        <span class="subtle">Last updated on: {{ $office->updated_at->format('d/m/Y H:i') }}</span>
    </div>
    <div class="text-center">
        <a class="subtle subtle-danger" id="remove-office-toggle" href="#remove-office-modal"><i class="fa fa-remove"></i> remove this office?</a>
    </div>

</div>