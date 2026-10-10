<?php

namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToTenant
{
    /**
     * Boot the trait.
     * Automatically assign tenant_id and apply tenant global scope.
     */
    protected static function bootBelongsToTenant(): void
    {
        // Automatically assign tenant_id when creating a record.
        static::creating(function ($model): void {
            $tenantId = Auth::user()?->tenant_id;

            if (empty($model->tenant_id) && !empty($tenantId)) {
                $model->tenant_id = $tenantId;
            }
        });

        // Automatically filter records by the authenticated tenant.
        static::addGlobalScope('tenant', function (Builder $builder): void {
            $tenantId = Auth::user()?->tenant_id;

            if (!empty($tenantId)) {
                $builder->where(
                    $builder->getModel()->qualifyColumn('tenant_id'),
                    $tenantId
                );
            }
        });
    }

    /**
     * Relationship to the tenant.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Filter records for a specific tenant.
     */
    public function scopeForTenant(
        Builder $query,
        int $tenantId
    ): Builder {
        return $query->withoutGlobalScope('tenant')
                     ->where(
                         $query->getModel()->qualifyColumn('tenant_id'),
                         $tenantId
                     );
    }
}
