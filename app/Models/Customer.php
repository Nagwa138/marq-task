<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'address',
        'tax_number',
        'balance',
        'type',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->type)) {
                $customer->type = 'individual';
            }
            if (empty($customer->balance)) {
                $customer->balance = 0;
            }
        });
    }

    /**
     * العلاقة مع الفواتير
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * العلاقة مع المدفوعات
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * حساب الرصيد الكلي
     */
    public function calculateBalance(): float
    {
        $totalInvoices = $this->invoices()->where('status', '!=', 'paid')->sum('total');
        $totalPayments = $this->payments()->sum('amount');

        return $totalInvoices - $totalPayments;
    }

    /**
     * تحديث الرصيد تلقائياً
     */
    public function updateBalance()
    {
        $this->balance = $this->calculateBalance();
        $this->save();
    }

    /**
     * Scope للعملاء النشطين
     */
    public function scopeActive($query)
    {
        return $query->whereHas('invoices', function ($q) {
            $q->where('status', '!=', 'paid');
        });
    }

    /**
     * Scope للعملاء المتأخرين في السداد
     */
    public function scopeOverdue($query)
    {
        return $query->where('balance', '>', 0)
            ->whereHas('invoices', function ($q) {
                $q->where('due_date', '<', now())
                    ->where('status', '!=', 'paid');
            });
    }

    // Accessor for invoices count
    public function getInvoicesCountAttribute()
    {
        return $this->invoices()->count();
    }

    // Accessor for paid invoices count
    public function getPaidInvoicesCountAttribute()
    {
        return $this->invoices()->where('status', 'paid')->count();
    }
}
