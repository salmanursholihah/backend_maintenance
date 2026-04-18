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
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
 
        if (!$user) {
            // API request → JSON, Web request → redirect login
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }
 
        if (!$user->is_active) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akun Anda dinonaktifkan.'], 403);
            }
            abort(403, 'Akun Anda dinonaktifkan.');
        }
 
        if (!in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses ke resource ini.',
                ], 403);
            }
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
 
        return $next($request);
    }
}
