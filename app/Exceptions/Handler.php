<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Bugsnag\BugsnagLaravel\BugsnagExceptionHandler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        /**
         * Catch a token mismatch and redirect user back to his current page
         *
         * When user session gets idle, the token will have to be refreshed
         * This is especially the case for ajax calls which keep on
         * making requests from the 'old session' page.
         */
        if ($e instanceof \Illuminate\Session\TokenMismatchException) {

            return $this->renderTokenMismatch($request);
        }

        if(config('app.debug')) return parent::render($request, $e);

        return $this->displayError($e);
    }

    /**
     * Render an exception into a response.
     * overrides the parent::render() method to provide custom 500 error page
     *
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    protected function displayError(Exception $e)
    {
        if ($this->isUnauthorizedException($e)) {
            $e = new HttpException(403, $e->getMessage());
        }

        if ($this->isHttpException($e)) {
            return $this->toIlluminateResponse($this->renderHttpException($e), $e);
        }

        $response = $this->convertExceptionToResponse($e);

        if($response->getStatusCode() == 500) return response()->view('errors.500',[],500);

        return $this->toIlluminateResponse($response, $e);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    private function renderTokenMismatch($request)
    {
        if ($request->ajax())
        {
            return response()->json([
                'status'  => 500,
                'error'   => 'tokenmismatchexception',
                'message' => trans('app.tokenmismatch')
            ], 500);
        }

        Session::flash('note.danger', trans('app.tokenmismatch'));

        return redirect()->to($request->header('HTTP_REFERER', '/'), 302, ["redirect-message" => "token was expired, redirecting to previous page"])
            ->withInput()
            ->with('token', csrf_token());
    }
}
