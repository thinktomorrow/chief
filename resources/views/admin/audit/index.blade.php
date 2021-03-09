@extends('chief::back._layouts.master')

@section('page-title','Audit')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Audit.')
@endcomponent

@section('content')
    <div class="stack-l">
        <section class="bg-white border border-grey-100 rounded inset-s stack-s">
                @forelse($audit as $event)
                    <div class="row border-b border-grey-100 py-2">
                        <div class="column-3">
                            {{ $event->getReadableCreatedAt() }}
                        </div>
                        <div class="column">
                            <h4 class="text-base mb-0">{{ $event->description }} {{ $event->getReadableSubject() }}</h4>
                            @if($event->causer)
                                <a href="{{route('chief.back.audit.show', $event->causer_id)}}">{{ $event->causer->fullname }}</a>
                            @endif
                        </div>
                    </div>
                @empty
                    Nog niet veel soeps hier... Better start your writing!
                @endforelse

                {{ $audit->links() }}
        </section>
    </div>

@stop
