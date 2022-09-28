<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, array|string ...$positions)
    {
        if (count($positions) == 0) {
            $positions = Role::getAllRoles();
        }

        if (auth()->check() && auth()->user()->checkRole($positions)) {
            return $next($request);
        } elseif (auth()->check()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Forbidden'], 403);
            } else {
                return redirect("/");
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } else {
            return redirect("/");
        }
    }
}
