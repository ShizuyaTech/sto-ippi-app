<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDirectUrlAccess
{
    /**
     * Handle an incoming request.
     *
     * Blocks direct URL access (typing in browser address bar).
     * Only allows navigation that originates from within the application,
     * or a page refresh of the last visited URL.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Non-GET requests are always from within the app (forms/AJAX)
        if (! $request->isMethod('GET')) {
            return $next($request);
        }

        $currentUrl = $request->url();
        $fetchSite = $request->headers->get('Sec-Fetch-Site');

        // Modern browsers send Sec-Fetch-Site header (Chrome, Firefox, Edge)
        if ($fetchSite !== null) {
            // 'same-origin' or 'same-site' means navigation came from within the app
            if ($fetchSite === 'same-origin' || $fetchSite === 'same-site') {
                session(['last_visited_url' => $currentUrl]);

                return $next($request);
            }

            // 'none' means direct URL access (address bar, bookmark, new tab)
            // Determine landing page and allow it to prevent loop
            $landingUrl = $request->user()?->isAdmin()
                ? route('admin.dashboard')
                : route('stock-taking.index');

            if ($landingUrl === $currentUrl) {
                session(['last_visited_url' => $currentUrl]);

                return $next($request);
            }

            return redirect($landingUrl);
        }

        // Fallback for older browsers that don't send Sec-Fetch-Site:
        // Allow if referer is from the same host (in-app navigation)
        $referer = $request->headers->get('referer');
        if ($referer && parse_url($referer, PHP_URL_HOST) === $request->getHost()) {
            session(['last_visited_url' => $currentUrl]);

            return $next($request);
        }

        // Allow page refresh: no referer but URL matches last visited page
        if (session('last_visited_url') === $currentUrl) {
            return $next($request);
        }

        // Allow the landing page after our own redirect
        if (session()->pull('_allow_next_nav')) {
            session(['last_visited_url' => $currentUrl]);

            return $next($request);
        }

        $landingUrl = $request->user()?->isAdmin()
            ? route('admin.dashboard')
            : route('stock-taking.index');

        if ($landingUrl === $currentUrl) {
            session(['last_visited_url' => $currentUrl]);

            return $next($request);
        }

        session(['_allow_next_nav' => true]);

        return redirect($landingUrl);
    }
}
