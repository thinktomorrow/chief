<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Admin\Users\Application\EnableUser;
use Thinktomorrow\Chief\Admin\Users\Invites\Application\SendInvite;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\InviteAccepted;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\UserInvited;
use Thinktomorrow\Chief\App\Listeners\LogSuccessfulLogin;
use Thinktomorrow\Chief\Fragments\Actions\DeleteFragment;
use Thinktomorrow\Chief\Fragments\Actions\UpdateFragmentMetadata;
use Thinktomorrow\Chief\Fragments\Events\FragmentAdded;
use Thinktomorrow\Chief\Fragments\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Events\FragmentDuplicated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\Site\Urls\Application\CreateUrlForPage;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Event::listen(Login::class, LogSuccessfulLogin::class, );
        Event::listen(UserInvited::class, SendInvite::class);
        Event::listen(InviteAccepted::class, EnableUser::class . '@onAcceptingInvite');

        Event::listen(ManagedModelCreated::class, CreateUrlForPage::class . '@onManagedModelCreated');
        Event::listen(FragmentDetached::class, DeleteFragment::class.'@onFragmentDetached');
        Event::listen(FragmentDetached::class, UpdateFragmentMetadata::class.'@onFragmentDetached');
        Event::listen(FragmentAdded::class, UpdateFragmentMetadata::class.'@onFragmentAdded');
        Event::listen(FragmentDuplicated::class, UpdateFragmentMetadata::class.'@onFragmentDuplicated');
    }
}
