<?php $formId = 'state-modal-form-' . \Illuminate\Support\Str::random(10); ?>

<div data-form data-form-tags="status,links">

    <form
            id="{{ $formId }}"
            action="@adminRoute('state-update', $model, $stateConfig->getStateKey() ,'archive')"
            method="POST"
    >
        @csrf
        @method('PUT')

        <h2 class="h2 h1-dark">Archiveer: {{ $resource->getPageTitle($model) }}</h2>

        @if(contract($model, \Thinktomorrow\Chief\Site\Visitable\Visitable::class))
            <p>
                Opgelet, dit haalt deze pagina van de site en bezoekers krijgen een 404-pagina te zien.
                Je kan ook kiezen om door te linken naar een andere pagina:
            </p>

            <select class="mt-3" name="redirect_id" id="redirectId">
                @foreach($targetModels as $targetModelGroup)
                    <option value="">---</option>
                    <optgroup label="{{ $targetModelGroup['group'] }}">
                        @foreach($targetModelGroup['values'] as $targetModel)
                            <option value="{{ $targetModel['id'] }}">{{ $targetModel['label'] }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>

        @else
            <p>Archiveren haalt de {{ $resource->getPageTitle($model) }} onmiddellijk van de site.</p>
        @endif
    </form>

    @if($content = $stateConfig->getTransitionContent( 'archive' ))
        <div class="prose prose-dark">
            <p>{!! $stateConfig->getTransitionContent( 'archive' ) !!}</p>
        </div>
    @endif

    <div class="flex items-center mt-8 space-x-4">
        <button
            form="{{ $formId }}"
            type="submit"
            class="btn btn-primary btn-{{ $stateConfig->getTransitionType('archive') }}"
        >
            {{ $stateConfig->getTransitionButtonLabel('archive') }}
        </button>
    </div>
</div>
