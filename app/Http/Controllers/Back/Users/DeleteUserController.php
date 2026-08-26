<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Users;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Thinktomorrow\Chief\Admin\Users\Application\DeleteUser;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class DeleteUserController extends Controller
{
    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('delete-user');

        if ((int) chiefAdmin()->getAuthIdentifier() === $id) {
            return redirect()->back()
                ->with('messages.error', 'Je kan jouw eigen account niet verwijderen.');
        }

        $user = User::findOrFail($id);

        if ($user->hasRole('developer') && ! chiefAdmin()->hasRole('developer')) {
            throw new AuthorizationException('Constraint: Only a user with role developer can delete a user with developer role.');
        }

        app(DeleteUser::class)->handle($user);

        return redirect()->route('chief.back.users.index')
            ->with('messages.success', 'De gebruikersaccount is definitief verwijderd.');
    }
}
