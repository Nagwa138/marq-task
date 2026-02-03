<?php

namespace App\Traits;

trait BelongsToTenant
{
    public static function bootBelongsToTenant()
    {
        static::creating(function ($model) {
            if (!$model->tenant_id && auth()->check()) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });

        static::addGlobalScope(new \App\Scopes\TenantScope);
    }
}
