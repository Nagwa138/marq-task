<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'database',
        'company_name',
        'email',
        'phone',
        'address',
        'tax_number',
        'logo',
        'tax_rate',
        'currency',
        'language',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'tax_rate' => 'decimal:2',
    ];

    /**
     * العلاقة مع المستخدمين
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * العلاقة مع العملاء
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * العلاقة مع الفواتير
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * العلاقة مع المدفوعات
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * الحصول على الإعدادات
     */
    public function getSetting(string $key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }
}
