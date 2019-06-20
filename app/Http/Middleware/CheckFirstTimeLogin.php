<?php

namespace Gcr\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFirstTimeLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isFirstTimeLoginRoute = $request->routeIs(
            'dashboard.user.first.time.login',
            'dashboard.user.first.time.login.show'
        );

        $user = auth()->user();

        if (!$isFirstTimeLoginRoute && ($user && $user->password_change_at == null))
        {
            return redirect()->route('dashboard.user.first.time.login.show');
        }
        return $next($request);
    }
}
