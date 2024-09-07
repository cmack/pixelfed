<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCuratedRegistration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless((bool) config_cache('instance.curated_registration.enabled'), 404);

        if ((bool) config_cache('pixelfed.open_registration')) {
            abort_if(config('instance.curated_registration.state.only_enabled_on_closed_reg'), 404);
        } else {
            abort_unless(config('instance.curated_registration.state.fallback_on_closed_reg'), 404);
        }

        return $next($request);
    }
}
