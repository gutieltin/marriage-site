<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = config('wedding.admin_token', config('admin_token'));
        $given = (string) $request->header('X-Admin-Token', '');

        if (! $token || ! hash_equals($token, $given)) {
            abort(401, 'Unauthorized');
        }

        return $next($request);
    }
}
