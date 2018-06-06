<?php

namespace Thinktomorrow\Chief\App\Http\Middleware;

use Closure;
use Thinktomorrow\Chief\Users\Invites\Invitation;
use Thinktomorrow\Chief\Users\Invites\InvitationState;

class ChiefValidateInvite
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Verifies a valid signature and still outside expiration period
        if (! $request->hasValidSignature()) {
            return redirect()->route('invite.expired');
        }

        if (! $invitation = Invitation::findByToken($request->token)) {
            return redirect()->route('invite.expired');
        }

        if ($invitation->state() == InvitationState::REVOKED) {
            return redirect()->route('invite.expired');
        }

        return $next($request);
    }
}
