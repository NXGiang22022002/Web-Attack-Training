<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeacher
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        
        if (!$user || (int) $user->isteacher !== 1) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}