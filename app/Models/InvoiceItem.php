<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'description',
        'quantity',
        'unit_price',
        'total',
        'tax_rate',
        'tax_amount',
        'unit',
        'discount',
        'discount_type',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            // حساب المجموع تلقائياً
            $item->calculateTotal();
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
     * حساب المجموع للبند
     */
    public function calculateTotal(): void
    {
        $total = $this->quantity * $this->unit_price;

        // تطبيق التخفيض على البند
        if ($this->discount > 0) {
            if ($this->discount_type === 'percentage') {
                $total = $total - ($total * ($this->discount / 100));
            } else {
                $total = $total - $this->discount;
            }
        }

        $this->total = $total;

        // حساب الضريبة للبند
        if ($this->tax_rate > 0) {
            $this->tax_amount = $this->total * ($this->tax_rate / 100);
        } else {
            $this->tax_amount = 0;
        }
    }

    /**
     * الحصول على السعر بعد الضريبة
     */
    public function getUnitPriceWithTaxAttribute(): float
    {
        return $this->unit_price + ($this->unit_price * ($this->tax_rate / 100));
    }

    /**
     * الحصول على المجموع بعد الضريبة
     */
    public function getTotalWithTaxAttribute(): float
    {
        return $this->total + $this->tax_amount;
    }
}
