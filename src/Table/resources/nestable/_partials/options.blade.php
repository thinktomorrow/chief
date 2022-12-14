<options-dropdown class="link link-primary">
    <div v-cloak class="dropdown-content">
        @adminCan('edit', $model)
            <a href="@adminRoute('edit', $node->getId())" title="Aanpassen" class="dropdown-link">
                Aanpassen
            </a>
        @endAdminCan

        @adminCan('preview', $model)
            <a href="{{ $node->url() }}" title="Bekijk op site" target="_blank" class="dropdown-link">
                Bekijk op site
            </a>
        @endAdminCan

        @adminCan('duplicate', $model)
            <button
                type="submit"
                form="duplicateForm-{{ $model->id }}"
                class="cursor-pointer dropdown-link dropdown-link-success"
            >
                Kopieer
            </button>

            <form
                id="duplicateForm-{{ $model->id }}"
                action="@adminRoute('duplicate', $node->getId())"
                method="POST"
                class="hidden"
            >
                {{ csrf_field() }}
            </form>
        @endAdminCan

        {{-- @foreach(['draft', 'publish', 'unpublish', 'unarchive'] as $action)
            @adminCan($action, $model)
                @include('chief::manager._transitions.index.'. $action, ['style' => 'dropdown-link'])
            @endAdminCan
        @endforeach --}}
    </div>
</options-dropdown>
