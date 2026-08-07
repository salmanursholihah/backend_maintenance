<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    //     public function handle(Request $request, Closure $next, String $role): Response
    //     {
    //         $user = $request->user();

    //         if (!$user) {
    //             return response()->json([
    //                 'message' => 'Unauthenticated',
    //             ], 401);
    //         }

    //         if ($user->role !== $role) {
    //             return response()->json([
    //                 'message' => 'Forbidden: wrong role',
    //             ], 403);
    //         }
    //         return $next($request);
    //     }
    // }


    ///code 2
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated',], 401);
            }
            return redirect()->route('login');
        }
        if ($user->role !== $role) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden: wrong role',], 403);
            }
            abort(403, 'Forbidden');
        }
        return $next($request);
    }
}




