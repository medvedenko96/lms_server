<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if($token = $request->cookie('token')) {
            $request->headers->set('Authorization', 'Bearer '.$token);
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }
}
