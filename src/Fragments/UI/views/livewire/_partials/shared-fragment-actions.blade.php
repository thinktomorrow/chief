@php
    use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
    use Thinktomorrow\Chief\Fragments\Fragment;
@endphp

@if($fragment->isShared)
    <div class="p-6 border border-orange-100 rounded-xl bg-orange-50">
        <p class="text-lg h6 h1-dark">Gedeeld fragment</p>

        <div class="mt-4 prose prose-dark prose-spacing">
            <p>
                Dit is een gedeeld fragment. Dat betekent dat het ook toegevoegd werd op een andere plaats
                op de website. Elke aanpassing aan dit fragment zal dus doorgevoerd worden op de volgende pagina's:

                {{--                @php--}}
                {{--                    $otherOwners = collect(app(GetOwners::class)--}}
                {{--                        ->getSharedFragmentDtos($model->getFragmentModel()))--}}
                {{--                        ->reject(function($otherOwner) use ($owner) {--}}
                {{--                            return $otherOwner['model']->modelReference()->equals($owner->modelReference());--}}
                {{--                        });--}}
                {{--                @endphp--}}

                @foreach($fragment->sharedFragmentDtos as $sharedFragmentDto)
                    <a
                        href="{{ $sharedFragmentDto->ownerAdminUrl }}"
                        title="{{ $sharedFragmentDto->ownerLabel }}"
                        class="underline link link-primary"
                    >
                        {{ $sharedFragmentDto->ownerLabel }}
                    </a>

                    @if(!$loop->last)
                        ,
                    @endif
                @endforeach
            </p>

            <p>
                Wil je een aanpassing maken aan dit fragment zonder dat je die doorvoert op de andere
                pagina's?
                Koppel het fragment dan los op deze pagina.
            </p>

            <p>
                <button
                    type="button"
                    wire:click="isolateFragment"
                    class="btn btn-warning-outline"
                >
                    Fragment loskoppelen en afzonderlijk bewerken
                </button>
            </p>
        </div>
    </div>
@endif
