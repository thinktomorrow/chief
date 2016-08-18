<div class="form-group">
    <label class="control-label" for="inputFaq">Position on site:</label>
    <i class="fa fa-question-circle" data-toggle="tooltip" title="Drag and drop your faq to its new position."></i>
    <div class="bs-component">
        <ul class="list-group sortable">

            @foreach(\BNP\Faqs\Faq::getAll() as $sibling)

                <?php $current = ($sibling->id === $faq->id) ? ' current' : null; ?>

                <li class="list-group-item{{$current}}">
                    <input type="hidden" name="sequence[]" value="{{ $sibling->id }}">
                    <span title="{{ $sibling->title }}">{{ $sibling->title }}</span>
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
        <span class="subtle">Last updated on: {{ $faq->updated_at->format('d/m/Y H:i') }}</span>
    </div>
    <div class="text-center">
        <a class="subtle subtle-danger" id="remove-faq-toggle" href="#remove-faq-modal"><i class="fa fa-remove"></i> remove this faq?</a>
    </div>

</div>