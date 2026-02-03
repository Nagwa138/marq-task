<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
        'company_id',
        'role',
        'phone',
        'avatar',
        'last_active_company_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->role)) {
                $user->role = 'accountant';
            }
        });
    }

    /**
     * العلاقة مع Tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * العلاقة مع Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * التحقق إذا كان المستخدم مدير
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * التحقق إذا كان المستخدم محاسب
     */
    public function isAccountant(): bool
    {
        return $this->role === 'accountant';
    }

    /**
     * التحقق إذا كان المستخدم مشاهد فقط
     */
    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }

    /**
     * Scope للمستخدمين النشطين
     */
    public function scopeActive($query)
    {
        return $query->whereHas('tenant', function ($q) {
            $q->where('is_active', true);
        });
    }
}
