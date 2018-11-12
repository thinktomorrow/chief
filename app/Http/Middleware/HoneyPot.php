<?php

namespace Thinktomorrow\Chief\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HoneyPot
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
        $this->honeypot($request);
        $this->timer($request);

        return $next($request);
    }

    /**
     * Honeypot field protection
     *
     * A hidden field in the comment form is tagged as honeypot.
     * Should this field be filled with data or if this field is removed
     * from the input, We can assume the submit is forged.
     *
     * A field with the attribute key of your_name is assumed
     */
    private function honeypot(Request $request)
    {
        if (!$request->exists('your_name') or $request->get('your_name') != null) {
            abort('403', 'Request blocked due to assumed spam attempt. Honeypot field was filled in.');
        }
    }

    /**
     * Timer lock
     *
     * Should the request be performed in less then 3 seconds
     * A automatic spam submit is assumed.
     * Validation is performed by setting a timestamp
     * at the time of the comment form creation
     */
    private function timer(Request $request)
    {
        if (!$request->exists('_timer') or (time()-2) <= $request->get('_timer')) {
            abort('403', 'Request blocked due to assumed spam attempt. Submission happened too fast.');
        }
    }
}
