<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {

                // get ability middleware parameter
                $action = $request->route()->getAction();
                $ability = isset($action['middleware'][2]) ? $action['middleware'][2] : null;
                if ($ability) {
                    if ($ability == 'ability:' . \App\Enums\TokenAbility::ISSUE_ACCESS_TOKEN->value) {
                        return response()->json([
                            'ok' => false,
                            'err' => 'ERR_INVALID_REFRESH_TOKEN',
                            'msg' => 'invalid refresh token'
                        ], 401);
                    }
                }

                return response()->json([
                    'ok' => false,
                    'err' => 'ERR_INVALID_ACCESS_TOKEN',
                    'msg' => 'invalid access token'
                ], 401);
            }
        });

        // access denied exception
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'ok' => false,
                    'err' => 'ERR_FORBIDDEN_ACCESS',
                    'msg' => 'user doesn\'t have access to this resource'
                ], 403);
            }
        });

        // route not found exception
        $this->renderable(function (\Symfony\Component\Routing\Exception\RouteNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'ok' => false,
                    'err' => 'ERR_NOT_FOUND',
                    'msg' => 'resource not found'
                ], 404);
            }
        });

        // internal server error exception
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($request->is('api/*')) {
                $message = 'internal server error';
                if (env('APP_DEBUG')) {
                   $message = $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
                }
                return response()->json([
                    'ok' => false,
                    'err' => 'ERR_INTERNAL_SERVER_ERROR',
                    'msg' => $message
                ], 500);
            }
        });

        // query exception
        $this->renderable(function (\Illuminate\Database\QueryException $e, $request) {
            if ($request->is('api/*')) {
                $message = 'internal server error';
                if (env('APP_DEBUG')) {
                   $message = $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
                }
                return response()->json([
                    'ok' => false,
                    'err' => 'ERR_INTERNAL_SERVER_ERROR',
                    'msg' => $message
                ], 500);
            }
        });

        // error exception
        $this->renderable(function (\ErrorException $e, $request) {
            if ($request->is('api/*')) {
                $message = 'internal server error';
                if (env('APP_DEBUG')) {
                    $message = $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
                }
                return response()->json([
                    'ok' => false,
                    'err' => 'ERR_INTERNAL_SERVER_ERROR',
                    'msg' => $message
                ], 500);
            }
        });
    }
}
