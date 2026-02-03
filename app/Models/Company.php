<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Company extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'address',
        'tax_number',
        'logo',
        'website',
        'contact_person',
        'contact_email',
        'contact_phone',
    ];

    /**
     * العلاقة مع المستخدمين
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * العلاقة مع الفواتير
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * الحصول على صورة الشعار
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }

        return asset('images/default-company-logo.png');
    }
}
