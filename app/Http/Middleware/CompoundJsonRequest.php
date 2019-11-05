<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * If the request has 'json' field, then request will be transformed into regular json request.
 * This is needed because it is impossible to send files in regular json request, so we send a
 * regular FormData request with 'json' field and (optionally) files.
 */
class CompoundJsonRequest
{
    public function handle(Request $request, Closure $next)
    {
        if (null !== $model = $request->get('json')) {
            $parsed = json_decode($model, true);

            $request->setJson(new ParameterBag($parsed));
            $request->headers->set('CONTENT_TYPE', 'application/json');
        }

        return $next($request);
    }
}
