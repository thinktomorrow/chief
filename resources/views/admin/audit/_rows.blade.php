<div class="row">
    <div class="w-full">
        <x-chief::window>
            <div class="-my-4 divide-y divide-grey-100">
                @forelse($audit as $event)
                    <div class="py-4 space-y-1">
                        <div>
                            <span class="font-medium text-grey-900">
                                {{ $event->description }} {{ $event->getReadableSubject() }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            @if($event->causer)
                                <a href="{{route('chief.back.audit.show', $event->causer_id)}}" class="link link-primary">{{ $event->causer->fullname }}</a>
                            @endif

                            <span class="text-grey-500">{{ $event->getReadableCreatedAt() }}</span>
                        </div>
                    </div>
                @empty
                    Nog niet veel soeps hier... Better start your writing!
                @endforelse

                {{ $audit->links('chief::pagination.default') }}
            </div>
        </x-chief::window>
    </div>
</div>
