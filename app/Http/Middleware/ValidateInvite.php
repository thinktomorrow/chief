<?php

namespace App\Http\Middleware;

use Chief\Users\Invites\Invitation;
use Chief\Users\Invites\InvitationState;
use Closure;
use Illuminate\Http\Request;

class ValidateInvite
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

        if(! $invitation = Invitation::findByToken($request->token)) {
            return redirect()->route('invite.expired');
        }

        if($invitation->state() == InvitationState::REVOKED) {
            return redirect()->route('invite.expired');
        }

        return $next($request);
    }
}