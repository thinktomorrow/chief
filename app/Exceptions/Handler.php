<?php

namespace Thinktomorrow\Chief\App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

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
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof AuthorizationException) {
            return $this->unauthorized($request, $e);
        }

        if ($request->getMethod() == 'POST' && $e instanceof PostTooLargeException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => true, // required by redactor
                    'message' => $e->getMessage(),
                ], 200);
            }
        }
        if ($this->shouldRenderChiefException($e)) {
            return $this->renderChiefException($request, $e);
        }

        return parent::render($request, $e);
    }

    private function shouldRenderChiefException(Throwable $exception): bool
    {
        return (Str::startsWith(request()->path(), 'admin/') && ! $exception instanceof AuthenticationException && ! $exception instanceof ValidationException);
    }

    protected function renderChiefException(\Illuminate\Http\Request $request, Throwable $exception)
    {
        if (! config('app.debug')) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Something went wrong.'], 404);
            }

            return response()->view('chief::back.errors.custom', [], 500);
        }

        return parent::render($request, $exception);
    }


    protected function unauthorized(\Illuminate\Http\Request $request, AuthorizationException $exception)
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

        if (! empty($exception->guards()) && Arr::first($exception->guards()) == 'chief') {
            return redirect()->guest(route('chief.back.login'));
        }

        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest(method_exists($exception, 'redirectTo') ? $exception->redirectTo() : '/');
    }
}
