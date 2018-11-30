<!-- Here you find the specific chief navigation for your project -->
{!! Thinktomorrow\Chief\Nav\Nav::fromKeys('singles')->render('Algemeen') !!}

{!! Thinktomorrow\Chief\Nav\Nav::fromTags('page')
    ->rejectKeys('singles')
    ->add('Modules', route('chief.back.modules.index'))
    ->renderItems('Collecties')
!!}

{!! Thinktomorrow\Chief\Nav\Nav::allManagers()
        ->rejectKeys('singles')
        ->rejectTags(['page','module'])
        ->renderItems('Models')
!!}