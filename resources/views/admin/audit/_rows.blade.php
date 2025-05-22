<div class="flex flex-wrap items-start justify-start">
    <div class="w-full">
        <x-chief::window>
            <div class="divide-grey-100 -my-4 divide-y">
                @forelse ($audit as $event)
                    <div class="space-y-1 py-4">
                        <div>
                            <span class="body-dark font-medium">
                                {{ ucfirst($event->description) }} {{ $event->getReadableSubject() }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            @if ($event->causer)
                                <x-chief::link
                                    href="{{ route('chief.back.audit.show', $event->causer_id) }}"
                                    title="{{ $event->causer->fullname }}"
                                    variant="blue"
                                >
                                    {{ $event->causer->fullname }}
                                </x-chief::link>
                            @endif

                            <span class="text-grey-500">{{ $event->getReadableCreatedAt() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-grey-500 py-4">Nog niet veel soeps hier... Better start your writing!</div>
                @endforelse

                {{ $audit->links('chief::pagination.default') }}
            </div>
        </x-chief::window>
    </div>
</div>
