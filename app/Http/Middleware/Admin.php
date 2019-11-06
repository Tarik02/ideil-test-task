<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class Admin
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
        if (null !== $user = \Auth::user()) {
            /** @var User $user */

            if ($user->hasRole('admin')) {
                return $next($request);
            }
        }

        return abort(403, 'Forbidden');
    }
}
