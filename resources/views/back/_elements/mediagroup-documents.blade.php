<section class="formgroup">
    <div class="row gutter-xl">
        <div class="formgroup-info column-4">
            <h2 class="formgroup-label">{{ $label ?? 'Media' }}</h2>
            <p>{!! $description ?? '' !!}</p>
        </div>
        <div class="column-8">

                @foreach($page->getAllFiles(\Thinktomorrow\Chief\Media\MediaType::DOCUMENT) as $document)
                    <div class="panel panel-default inset-s stack-s center-y bg-white">
                        <div>
                            <strong>{{ $document->getFilename() }}</strong>
                            <br>
                            <span class="text-subtle">
                                {{ $document->getExtensionType() }} | {{ $document->getSize() }}
                            </span>
                        </div>

                        <div style="margin-left:auto;">
                            <a href="{{ url($document->getFileUrl()) }}" target="_blank">Bekijk document</a>
                        </div>
                    </div>
                @endforeach

            <label for="">Voeg document toe:</label>
            <input type="file" name="files[{{ $group }}][new][]" multiple style="opacity:1; position:static;"/>
        </div>
    </div>
</section>
