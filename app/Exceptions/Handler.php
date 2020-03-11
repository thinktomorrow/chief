<?php

namespace Thinktomorrow\Chief\App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof AuthorizationException) {
            return $this->unauthorized($request, $exception);
        }

        //could use some code cleanup
        if ((strpos(url()->previous(), 'admin') || strpos(url()->current(), 'admin')) && !$exception instanceof AuthenticationException && !$exception instanceof ValidationException) {
            return $this->renderChiefException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    protected function renderChiefException($request, Exception $exception)
    {
        if (!config('app.debug')) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Something went wrong.'], 404);
            }

            return response()->view('chief::back.errors.custom');
        }
        
        return parent::render($request, $exception);
    }


    protected function unauthorized($request, AuthorizationException $exception)
    {
        return redirect()->route('chief.back.dashboard')
                         ->with('messages.error', 'Oeps. Het lijkt erop dat je geen toegang hebt tot dit deel van chief. Vraag even de beheerder voor meer info.');
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        if (!empty($exception->guards()) && array_first($exception->guards()) == 'chief') {
            return redirect()->guest(route('chief.back.login'));
        }

        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest($exception->redirectTo() ?? '/');
    }
}
