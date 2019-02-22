<?php
    $page = $manager->model();
?>

<h2>Eigen modules</h2>
<p>Hier vind je alle modules (blokken) die specifiek zijn voor deze pagina. Je kan deze op de pagina plaatsen door
    ze te selecteren in de <a href="#pagina">pagina tab</a></p>
@if($page->modules->isEmpty())
    <div class="center-center stack-xl">
        <div>
            <a @click="showModal('create-module')" class="btn btn-primary squished">
            <i class="icon icon-zap icon-fw"></i> Voeg een eerste module toe specifiek voor deze pagina.
            </a>
        </div>
    </div>
@endif

@if(!$page->modules->isEmpty())
    <div class="row gutter-s">
        @foreach($page->modules->reject(function($module){ return $module->morph_key == 'pagetitle'; }) as $module)
            @include('chief::back.managers._partials._rowitem', ['manager' => app(\Thinktomorrow\Chief\Management\Managers::class)->findByModel($module)])
        @endforeach
    </div>


    <div class="stack">
        <a @click="showModal('create-module')" class="btn btn-primary">
        <i class="icon icon-plus"></i>
        Voeg een module toe
        </a>
    </div>
@endif

@push('portals')
    @include('chief::back.modules._partials.create-modal', ['page_id' => $page->id])
@endpush
