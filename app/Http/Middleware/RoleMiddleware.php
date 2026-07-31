<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user()) {
            return redirect('/office/login');
        }


        if (!$request->user()->role) {
            abort(403);
        }


        if ($request->user()->role->name !== $role) {
            abort(403);
        }


        return $next($request);
    }
}