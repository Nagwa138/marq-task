<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Payment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'invoice_id',
        'customer_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference',
        'notes',
        'status',
        'received_by',
        'bank_name',
        'check_number',
        'transaction_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_date)) {
                $payment->payment_date = now();
            }

            if (empty($payment->status)) {
                $payment->status = 'completed';
            }

            // توليد رقم مرجعي
            if (empty($payment->reference)) {
                $payment->reference = 'PAY-' . strtoupper(uniqid());
            }
        });

        static::created(function ($payment) {
            // تحديث رصيد العميل
            $payment->customer?->updateBalance();

            // تحديث حالة الفاتورة إذا تم دفعها بالكامل
            $payment->invoice?->refresh();
            if ($payment->invoice?->isFullyPaid()) {
                $payment->invoice->markAsPaid();
            }
        });

        static::deleted(function ($payment) {
            // تحديث الرصيد عند حذف الدفعة
            $payment->customer?->updateBalance();
        });
    }

    /**
     * العلاقة مع الفاتورة
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * العلاقة مع العميل
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * العلاقة مع المستخدم الذي استلم الدفعة
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Scope للمدفوعات الناجحة
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope للمدفوعات الفاشلة
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope للمدفوعات المعلقة
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * الحصول على اسم طريقة الدفع
     */
    public function getPaymentMethodNameAttribute(): string
    {
        $methods = [
            'cash' => 'نقدي',
            'bank_transfer' => 'تحويل بنكي',
            'credit_card' => 'بطاقة ائتمان',
            'check' => 'شيك',
            'online' => 'دفع إلكتروني',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }
}
