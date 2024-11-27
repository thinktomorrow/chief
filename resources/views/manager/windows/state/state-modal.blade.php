<x-chief::dialog.modal id="state-modal-{{ $transitionKey }}-{{ $model->id }}" title="Ben je zeker?" size="xs">
    @if ($asyncModalUrl = $stateConfig->getAsyncModalUrl($transitionKey, $model))
        <div
            x-data="{ customHtml: null }"
            x-html="customHtml"
            x-init="
                $watch('open', (value) => {
                    fetch('{{ $stateConfig->getAsyncModalUrl($transitionKey, $model) }}')
                        .then((response) => response.json())
                        .then((data) => {
                            customHtml = data.data
                        })
                        .catch((error) => {
                            console.error(error)
                        })
                })
            "
        ></div>
    @else
        <form
            id="state-modal-{{ $transitionKey }}-{{ $model->id }}-form"
            action="@adminRoute('state-update', $model, $stateConfig->getStateKey(), $transitionKey)"
            method="POST"
            v-cloak
        >
            @csrf
            @method('PUT')

            <div class="space-y-6">
                @foreach ($stateConfig->getTransitionFields($transitionKey, $model) as $field)
                    {{ $field->render() }}
                @endforeach

                @if ($content = $stateConfig->getTransitionContent($transitionKey))
                    <div class="prose prose-dark">
                        <p>{!! $content !!}</p>
                    </div>
                @endif
            </div>
        </form>
    @endif

    <x-slot name="footer">
        <button type="submit" x-on:click="open = false" class="btn btn-grey">Annuleer</button>

        <button
            type="submit"
            form="state-modal-{{ $transitionKey }}-{{ $model->id }}-form"
            class="btn btn-primary btn-{{ $stateConfig->getTransitionType($transitionKey) }}"
        >
            {{ $stateConfig->getTransitionButtonLabel($transitionKey) }}
        </button>
    </x-slot>
</x-chief::dialog.modal>
