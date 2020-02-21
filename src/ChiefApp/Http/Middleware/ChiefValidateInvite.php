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

        if (in_array($invitation->state(), [InvitationState::ACCEPTED, InvitationState::REVOKED])) {

            // We allow the user to pass if the invitee is already logged in. Otherwise the invite link cannot be reused.
            if (! auth()->guard('chief')->check() || ! auth()->guard('chief')->user()->is($invitation->invitee)) {
                return redirect()->route('invite.expired');
            }
        }

        return $next($request);
    }
}
