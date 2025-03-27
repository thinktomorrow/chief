<x-slot name="title">
    {{ $this->getTitle() }}
</x-slot>

<x-slot name="subtitle">
    {!! $this->getContent() !!}
</x-slot>

<div class="space-y-6">
    <div class="space-y-3">
        @foreach ($this->getTransitions() as $transition)
            <x-chief::callout
                :title="$transition->title"
                :variant="match($transition->key) {
                                'publish' => 'grey',
                                'archive' => 'orange',
                                'delete' => 'red',
                                default => 'grey',
                            }"
            >
                <div class="space-y-2">
                    @if ($transition->content)
                        <div class="prose prose-dark">
                            <p>{!! $transition->content !!}</p>
                        </div>
                    @endif

                    <x-chief::button
                        x-data
                        x-on:click="$wire.transition('{{ $transition->key }}')"
                        :variant="match($transition->key) {
                                        'publish' => 'outline-blue',
                                        'archive' => 'outline-orange',
                                        'delete' => 'outline-red',
                                        default => 'outline-white',
                                    }"
                    >
                        {{ $transition->label }}
                    </x-chief::button>
                </div>
            </x-chief::callout>
        @endforeach
    </div>
</div>
