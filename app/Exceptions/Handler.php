<?php

namespace Thinktomorrow\Chief\App\Exceptions;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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

        if ($request->getMethod() == 'POST' && $exception instanceof PostTooLargeException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => true, // required by redactor
                    'message' => $exception->getMessage(),
                ], 200);
            }

//            return redirect()->back()->withInput()->withErrors($exception->getMessage());
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

            return response()->view('chief::back.errors.custom', [], 500);
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

        if (!empty($exception->guards()) && Arr::first($exception->guards()) == 'chief') {
            return redirect()->guest(route('chief.back.login'));
        }

        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest(method_exists($exception, 'redirectTo') ? $exception->redirectTo() : '/');
    }
}
