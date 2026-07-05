<?php

namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Tenantable
{
    protected static function bootTenantable(): void
    {
        // Auto-scope every query to the current tenant
        static::addGlobalScope('tenant', function (Builder $builder) {
            if ($tenantId = static::currentTenantId()) {
                $builder->where(
                    (new static)->getTable() . '.tenant_id',
                    $tenantId
                );
            }
        });

        // Auto-fill tenant_id on create
        static::creating(function (Model $model) {
            if (empty($model->tenant_id) && $tenantId = static::currentTenantId()) {
                $model->tenant_id = $tenantId;
            }
        });
    }

    /**
     * Resolve the current tenant ID. Override this if you resolve
     * tenants differently (subdomain, header, session, etc).
     */
    public static function currentTenantId(): ?string
    {
        return app()->bound('tenant')
            ? app('tenant')?->id
            : null;
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Escape hatch — query across all tenants.
     */
    public function scopeWithoutTenancy(Builder $query): Builder
    {
        return $query->withoutGlobalScope('tenant');
    }
}