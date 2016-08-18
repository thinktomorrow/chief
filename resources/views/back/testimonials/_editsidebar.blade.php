<div class="form-group">
    <label class="control-label" for="inputTestimonial">Featured image:</label>
    <i class="fa fa-question-circle" data-toggle="tooltip" title="Use a square image. It is preferred to use the profile picture of the referred person."></i>
    <div class="bs-component">
        @include('admin.testimonials._fileupload')
    </div>
</div>

<div class="form-group">
    <label class="control-label" for="inputTestimonial">Related to module:</label>
    <i class="fa fa-question-circle" data-toggle="tooltip" title="Testimonials will be shown on the selected module pages."></i>
    <div class="bs-component">
        {!! Form::select('module_ids[]',['' => '---'] + $moduleOptions,null,['class' => 'form-control','id' => 'inputTestimonial','multiple' => 'true']) !!}
    </div>
</div>

<div class="form-group">
    <label class="control-label" for="inputTestimonial">Position on site:</label>
    <i class="fa fa-question-circle" data-toggle="tooltip" title="Drag and drop your testimonial to its new position."></i>
    <div class="bs-component">
        <ul class="list-group sortable">

            @foreach(\BNP\Testimonials\Testimonial::sequence()->get() as $sibling)

                <?php $current = ($sibling->id === $testimonial->id) ? ' current' : null; ?>

                <li class="list-group-item{{$current}}">
                    <input type="hidden" name="sequence[]" value="{{ $sibling->id }}">
                    <span title="{{ $sibling->title }}">{{ $sibling->company.' - '.$sibling->name }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>


<div class="form-group">
    <label class="control-label" for="inputTestimonial">Type of display (page / popup):</label>
    <i class="fa fa-question-circle" data-toggle="tooltip" title="By default the testimonial is displayed on a separate page. It is shown as a popup if the content is short (less than 500 characters)."></i>
    <div class="bs-component">
        @if($testimonial->showOnFullpage())
            <span class="badge">page</span>
        @else
            <span class="badge">popup</span>
        @endif
    </div>
</div>

<div class="form-group">
    <div class="bs-component">
        {!! Form::checkbox('published',1,$testimonial->isPublished(),['id' => 'inputPublished']) !!}
        <label class="control-label" for="inputPublished">Publish testimonial</label>
    </div>
</div>

<hr>

<div class="form-group">

    <div class="bs-component text-center">
        <button class="btn btn-success btn-lg" type="submit"><i class="fa fa-check"></i> Save your changes</button>
    </div>
    <div class="text-center">
        <span class="subtle">Last updated on: {{ $testimonial->updated_at->format('d/m/Y H:i') }}</span>
    </div>
    <div class="text-center">
        <a class="subtle subtle-danger" id="remove-testimonial-toggle" href="#remove-testimonial-modal"><i class="fa fa-remove"></i> remove this testimonial?</a>
    </div>

</div>