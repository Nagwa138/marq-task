<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        $domain = $request->getHost();
        $tenant = Tenant::where('domain', $domain)->first();

        if ($tenant) {
            config(['database.connections.tenant.database' => $tenant->database]);
            config(['tenant.current' => $tenant]);
        }

        return $next($request);
    }
}
