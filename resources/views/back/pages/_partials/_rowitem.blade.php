<div class="row">
    <div class="column-8 stretched">
        <div class="column-12">
            <h2>
                {{ $page->getTranslationFor('title') }}
                <a title="Bekijk {{ $page->title }}" href="{{ $page->previewUrl() }}" target="_blank" class="text-subtle font-s">Preview</a>
            </h2>
        </div>
        <div class="column-12">
            {{ teaser($page->content,150,'...') }}
        </div>
        <span class="text-subtle">Laatst aangepast op: {{ $page->updated_at->format('d/m/Y') }}</span>
    </div>
    @if($page->isPublished())
        <div class="center-y column">
            <a href="{{ route('chief.back.pages.edit',$page->getKey()) }}" class="btn btn-link text-font">Aanpassen</a>
            <form action="{{ route('chief.back.pages.publish') }}" method="POST">
                {{ csrf_field() }}
                <div class="publishActions-{{$page->id}} hidden">
                    <input type="hidden" name="checkboxStatus" value="{{ $page->isPublished() }}">
                    <input type="hidden" name="id" value="{{ $page->id }}">
                    <button type="submit" class="btn btn-icon btn-subtle">Plaats in draft</button>
                </div>
            </form>
            <a @click="showModal('delete-page-{{$page->id}}')" class="btn btn-link"><i class="icon icon-trash icon-fw"></i></a>
        </div>
    @else
        <div class="center-y column">
            <a href="{{ route('chief.back.pages.edit',$page->getKey()) }}" class="btn btn-link text-font">Aanpassen</a>
            <form action="{{ route('chief.back.pages.publish') }}" method="POST">
                {{ csrf_field() }}
                <div class="publishActions-{{$page->id}} hidden">
                    <input type="hidden" name="checkboxStatus" value="{{ $page->isPublished() }}">
                    <input type="hidden" name="id" value="{{ $page->id }}">
                    <button type="submit" class="btn btn-subtle">Publiceer</button>
                </div>
            </form>
            <a @click="showModal('delete-page-{{$page->id}}')" class="btn btn-link"><i class="icon icon-trash icon-fw"></i></a>
        </div>
    @endif
</div>
<hr>