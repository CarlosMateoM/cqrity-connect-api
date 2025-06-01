<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IsUserEnabledMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        Log::info('Checking if user is enabled', [
            'user_id' => $user->id,
            'is_active' => $user->is_active,
        ]);

        if (!$user->is_active) {

            return response()->json([
                'status' => 'user_is_not_enabled',
                'message' => 'User is not enabled'
            ], 403);

        }

        return $next($request);
    }
}
