<div class="row bg-white inset panel panel-default stack">
    <div class="column-9">
            <a class="text-black bold" href="{{ $manager->route('edit') }}">
                {{ $module->slug }}
            </a>
            <div>
                <span class="text-subtle">{{ $module->collectionDetails()->singular }} | Aangepast op: {{ $module->updated_at->format('d/m/Y') }}</span>
            </div>
    </div>
    <div class="column-3 text-right">
        <options-dropdown class="inline-block">
            <div class="inset-s" v-cloak>
                <a href="{{ route('chief.back.modules.edit',$module->getKey()) }}" class="block squished-s --link-with-bg">Aanpassen</a>

                <hr class="stack-s">

                <a @click="showModal('delete-module-{{$module->id}}')" class="block squished-s text-error --link-with-bg">
                    Verwijder {{ $module->collectionDetails()->singular }}
                </a>
            </div>
        </options-dropdown>
    </div>
</div>