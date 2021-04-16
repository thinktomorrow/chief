<div class="row">
    <div class="w-full">
        <div class="window window-white">
            <div class="divide-y divide-grey-100 -m-12">
                @forelse($audit as $event)
                    <div class="px-12 py-4 space-y-1">
                        <div>
                            <span class="text-grey-900 font-medium">{{ $event->description }} {{ $event->getReadableSubject() }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            @if($event->causer)
                                <a href="{{route('chief.back.audit.show', $event->causer_id)}}" class="link link-primary">{{ $event->causer->fullname }}</a>
                            @endif

                            <span class="text-grey-500">{{ $event->getReadableCreatedAt() }}</span>
                        </div>
                    </div>
                @empty
                    Nog niet veel soeps hier... Better start your writing!
                @endforelse

                {{ $audit->links() }}
            </div>
        </div>
    </div>
</div>
