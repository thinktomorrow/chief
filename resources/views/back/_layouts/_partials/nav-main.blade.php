<!-- Here you find the specific chief navigation for your project -->
{!! Thinktomorrow\Chief\Nav\Nav::fromKeys(\Thinktomorrow\Chief\Pages\Single::registrationKey())->render('Pagina\'s') !!}

{!! Thinktomorrow\Chief\Nav\Nav::fromTags('page')
    ->rejectKeys(\Thinktomorrow\Chief\Pages\Single::registrationKey())
    ->renderItems('Collecties')
!!}

{!! Thinktomorrow\Chief\Nav\Nav::allManagers()
    ->rejectKeys(\Thinktomorrow\Chief\Pages\Single::registrationKey())
    ->rejectTags(['page','module', 'pagesection'])
    ->renderItems('Models')
!!}