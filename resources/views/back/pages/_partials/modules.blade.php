<?php
    $page = $manager->existingModel();
?>

<div class="stack-l formgroup">
    <h2>Pagina modules</h2>
    <p>Hier vind je alle modules (blokken) die specifiek zijn voor deze pagina. Je kan deze op de pagina plaatsen door
        ze te selecteren in de <a href="#pagina">pagina tab</a></p>
    @if($page->modules->isEmpty())
        <div class="stack-l">
            <div>
                <a @click="showModal('create-module')" class="btn btn-primary inline-flex items-center">
                    <span class="mr-2"><svg width="18" height="18"><use xlink:href="#zap"/></svg></span>
                    <span>Voeg een eerste module toe specifiek voor deze pagina.</span>
                </a>
            </div>
        </div>
    @endif

    @if(!$page->modules->isEmpty())

        <div class="row gutter-s stack">
            @foreach($page->modules->reject(function($module){ return $module->morph_key == 'pagetitle'; }) as $module)
                @include('chief::back.managers._partials._rowitem', ['manager' => app(\Thinktomorrow\Chief\Management\Managers::class)->findByModel($module)])
                @push('portals')
                    @include('chief::back.managers._modals.delete-modal', ['manager' => app(\Thinktomorrow\Chief\Management\Managers::class)->findByModel($module)])
                @endpush
            @endforeach
        </div>

        <div class="stack">
            <a @click="showModal('create-module')" class="btn btn-secondary inline-flex items-center">
                <span class="mr-2"><svg width="18" height="18"><use xlink:href="#add"/></svg></span>
                <span>Voeg een module toe</span>
            </a>
        </div>
    @endif

    @push('portals')
        @include('chief::back.modules._partials.create-modal', ['page_id' => $page->id])
    @endpush
</div>
