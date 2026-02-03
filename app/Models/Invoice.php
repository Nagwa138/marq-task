<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use Carbon\Carbon;

class Invoice extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'company_id',
        'customer_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'status',
        'notes',
        'payment_terms',
        'discount',
        'discount_type', // percentage, fixed
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            // توليد رقم فاتورة تلقائي
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = static::generateInvoiceNumber();
            }

            // تعيين الحالة الافتراضية
            if (empty($invoice->status)) {
                $invoice->status = 'draft';
            }
        });

        static::saving(function ($invoice) {
            // حساب التخفيض
            $invoice->calculateTotals();

            // تحديث حالة الفاتورة بناءً على تاريخ الاستحقاق
            if ($invoice->status !== 'paid' && $invoice->due_date < now()) {
                $invoice->status = 'overdue';
            }
        });
    }

    /**
     * العلاقة مع العميل
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * العلاقة مع الشركة
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * العلاقة مع بنود الفاتورة
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * العلاقة مع المدفوعات
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * توليد رقم فاتورة فريد
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $year = date('Y');
        $month = date('m');

        $lastInvoice = static::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}-{$year}{$month}-{$newNumber}";
    }

    /**
     * حساب المجموع الكلي للفاتورة
     */
    public function calculateTotals(): void
    {
        $subtotal = $this->items->sum('total');

        // تطبيق التخفيض
        if ($this->discount > 0) {
            if ($this->discount_type === 'percentage') {
                $subtotal = $subtotal - ($subtotal * ($this->discount / 100));
            } else {
                $subtotal = $subtotal - $this->discount;
            }
        }

        $this->subtotal = $subtotal;

        // حساب الضريبة (15%)
        $taxRate = $this->company?->tenant?->tax_rate ?? 15;
        $this->tax = $this->subtotal * ($taxRate / 100);

        // المجموع النهائي
        $this->total = $this->subtotal + $this->tax;
    }

    /**
     * حساب المبلغ المدفوع
     */
    public function getPaidAmountAttribute(): float
    {
        return $this->payments()->sum('amount');
    }

    /**
     * حساب المبلغ المتبقي
     */
    public function getRemainingAmountAttribute(): float
    {
        return $this->total - $this->paid_amount;
    }

    /**
     * التحقق إذا كانت الفاتورة مدفوعة بالكامل
     */
    public function isFullyPaid(): bool
    {
        return $this->remaining_amount <= 0;
    }

    /**
     * تغيير حالة الفاتورة إلى مدفوعة
     */
    public function markAsPaid(): void
    {
        $this->status = 'paid';
        $this->save();

        // تحديث رصيد العميل
        if ($this->customer) {
            $this->customer->updateBalance();
        }
    }

    /**
     * إرسال الفاتورة
     */
    public function send(): void
    {
        if ($this->status === 'draft') {
            $this->status = 'sent';
            $this->save();
        }
    }

    /**
     * Scope للفواتير المتأخرة
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'paid')
            ->where('due_date', '<', now());
    }

    /**
     * Scope للفواتير النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'paid')
            ->where('status', '!=', 'cancelled');
    }

    /**
     * الحصول على أيام التأخير
     */
    public function getDaysOverdueAttribute(): int
    {
        if ($this->due_date >= now() || $this->status === 'paid') {
            return 0;
        }

        return now()->diffInDays($this->due_date);
    }
}
