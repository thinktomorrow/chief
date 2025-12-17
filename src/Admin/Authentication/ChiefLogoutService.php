<?php

namespace Thinktomorrow\Chief\Admin\Authentication;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChiefLogoutService
{
    public function logout(Request $request): void
    {
        $guard = Auth::guard('chief');

        if ($user = $guard->user()) {
            // Invalidate remember token in DB
            $guard->getProvider()->updateRememberToken($user, null);
        }

        $guard->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
