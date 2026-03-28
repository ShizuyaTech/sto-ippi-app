<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Halaman tersebut hanya untuk user biasa.');
        }

        return $next($request);
    }
}
