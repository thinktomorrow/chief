<div class="row bg-white inset panel panel-default stack">
    <div class="column-9">
            <a class="text-black bold" href="{{ route('chief.back.modules.edit',$module->getKey()) }}">
                {{ $module->getTranslationFor('title') }}
            </a>
            <div>
                <span class="text-subtle">Aangepast op: {{ $module->updated_at->format('d/m/Y') }}</span>
                <a title="Bekijk {{ $module->title }}" href="{{ $module->previewUrl() }}" target="_blank" class="font-s text-border"><em>Preview</em></a>
            </div>
            <div class="stack-s">
                {{ teaser($module->content,250,'...') }}
            </div>
    </div>
    <div class="column-3 text-right">
        <options-dropdown class="inline-block">
            <div class="inset-s" v-cloak>
                <a href="{{ route('chief.back.modules.edit',$module->getKey()) }}" class="block squished-s --link-with-bg">Aanpassen</a>

                @if($module->isPublished())
                    <a data-submit-form="draftForm-{{$module->id}}" class="block squished-s --link-with-bg">Haal offline</a>

                    <form class="--hidden" id="draftForm-{{$module->id}}" action="{{ route('chief.back.modules.draft', $module->id) }}" method="POST">
                        {{ csrf_field() }}
                        <button type="submit">Unpublish</button>
                    </form>
                @elseif($module->isDraft())
                    <a data-submit-form="publishForm-{{$module->id}}" class="block squished-s --link-with-bg">Zet online</a>

                    <form class="--hidden" id="publishForm-{{$module->id}}" action="{{ route('chief.back.modules.publish', $module->id) }}" method="POST">
                        {{ csrf_field() }}
                        <button type="submit">Publish</button>
                    </form>
                @endif

                <hr class="stack-s">

                <a @click="showModal('delete-module-{{$module->id}}')" class="block squished-s text-error --link-with-bg">
                    Gooi {{ $module->collectionDetails('singular') }} weg
                </a>
            </div>
        </options-dropdown>
    </div>
</div>