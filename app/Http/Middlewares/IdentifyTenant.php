<?php

namespace App\Http\Middlewares;

use App\Models\Tenant;
use Closure;

class IdentifyTenant
{
    public function handle($request, Closure $next)
    {
        $tenant = Tenant::findOrFail($request->header('tenant_id'));

        app()->instance('tenant', $tenant);

        return $next($request);
    }
}