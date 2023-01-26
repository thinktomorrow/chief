<div class="row-start-start">
    <div class="w-full">
        <div class="card">
            <div class="-my-4 divide-y divide-grey-100">
                @forelse($audit as $event)
                    <div class="py-4 space-y-1">
                        <div>
                            <span class="font-medium body-dark">
                                {{ ucfirst($event->description) }} {{ $event->getReadableSubject() }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            @if ($event->causer)
                                <a
                                    href="{{ route('chief.back.audit.show', $event->causer_id) }}"
                                    title="{{ $event->causer->fullname }}"
                                    class="link link-primary"
                                >
                                    {{ $event->causer->fullname }}
                                </a>
                            @endif

                            <span class="text-grey-500">{{ $event->getReadableCreatedAt() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-4 text-grey-500">
                        Nog niet veel soeps hier... Better start your writing!
                    </div>
                @endforelse

                {{ $audit->links('chief::pagination.default') }}
            </div>
        </div>
    </div>
</div>
