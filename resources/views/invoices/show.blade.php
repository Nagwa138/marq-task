@extends('layouts.app')

@section('title', 'فاتورة #' . $invoice->invoice_number . ' - النظام المحاسبي')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="{{ route('invoices.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">
                            <i class="fas fa-file-invoice-dollar text-indigo-600 ml-2"></i>
                            فاتورة #{{ $invoice->invoice_number }}
                        </h1>
                        <p class="text-gray-600 mt-2">{{ $invoice->customer->name }}</p>
                    </div>
                </div>
                <div class="flex space-x-3 space-x-reverse">
                    <!-- Status Badge -->
                    <span class="px-4 py-2 rounded-full text-sm font-medium {{
                    $invoice->status == 'paid' ? 'bg-green-100 text-green-800' :
                    ($invoice->status == 'sent' ? 'bg-blue-100 text-blue-800' :
                    ($invoice->status == 'overdue' ? 'bg-red-100 text-red-800' :
                    'bg-gray-100 text-gray-800'))
                }}">
                    @if($invoice->status == 'paid')
                            <i class="fas fa-check-circle ml-1"></i>
                        @elseif($invoice->status == 'sent')
                            <i class="fas fa-paper-plane ml-1"></i>
                        @elseif($invoice->status == 'overdue')
                            <i class="fas fa-exclamation-triangle ml-1"></i>
                        @else
                            <i class="fas fa-pen ml-1"></i>
                        @endif
                        {{ $invoice->status }}
                </span>

                    @if($invoice->status == 'draft')
                        <a href="{{ route('invoices.edit', $invoice) }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-medium">
                            <i class="fas fa-edit ml-2"></i>
                            تعديل
                        </a>
                    @endif

                    @if($invoice->status == 'sent')
                        <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium">
                            <i class="fas fa-hand-holding-usd ml-2"></i>
                            تسديد دفعة
                        </a>
                    @endif

                    <a href="{{ route('invoices.download', $invoice) }}" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 font-medium">
                        <i class="fas fa-download ml-2"></i>
                        تحميل PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Invoice Header -->
        <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- From (Company) -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">من:</h3>
                    <div class="flex items-start">
                        @if($invoice->company->logo)
                            <img src="{{ $invoice->company->logo_url }}" alt="{{ $invoice->company->name }}" class="h-16 w-16 rounded-lg object-cover ml-4">
                        @else
                            <div class="h-16 w-16 rounded-lg bg-indigo-100 flex items-center justify-center ml-4">
                                <i class="fas fa-building text-2xl text-indigo-600"></i>
                            </div>
                        @endif
                        <div>
                            <h4 class="text-xl font-bold text-gray-800">{{ $invoice->company->name }}</h4>
                            @if($invoice->company->email)
                                <p class="text-gray-600"><i class="fas fa-envelope text-gray-400 ml-1"></i> {{ $invoice->company->email }}</p>
                            @endif
                            @if($invoice->company->phone)
                                <p class="text-gray-600"><i class="fas fa-phone text-gray-400 ml-1"></i> {{ $invoice->company->phone }}</p>
                            @endif
                            @if($invoice->company->address)
                                <p class="text-gray-600"><i class="fas fa-map-marker-alt text-gray-400 ml-1"></i> {{ $invoice->company->address }}</p>
                            @endif
                            @if($invoice->company->tax_number)
                                <p class="text-gray-600"><i class="fas fa-id-card text-gray-400 ml-1"></i> {{ $invoice->company->tax_number }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- To (Customer) -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">إلى:</h3>
                    <div class="flex items-start">
                        <div class="h-16 w-16 rounded-lg bg-blue-100 flex items-center justify-center ml-4">
                            <i class="fas fa-user text-2xl text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800">{{ $invoice->customer->name }}</h4>
                            @if($invoice->customer->email)
                                <p class="text-gray-600"><i class="fas fa-envelope text-gray-400 ml-1"></i> {{ $invoice->customer->email }}</p>
                            @endif
                            @if($invoice->customer->phone)
                                <p class="text-gray-600"><i class="fas fa-phone text-gray-400 ml-1"></i> {{ $invoice->customer->phone }}</p>
                            @endif
                            @if($invoice->customer->address)
                                <p class="text-gray-600"><i class="fas fa-map-marker-alt text-gray-400 ml-1"></i> {{ $invoice->customer->address }}</p>
                            @endif
                            @if($invoice->customer->tax_number)
                                <p class="text-gray-600"><i class="fas fa-id-card text-gray-400 ml-1"></i> {{ $invoice->customer->tax_number }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Invoice Details -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">رقم الفاتورة:</span>
                            <span class="font-bold text-gray-800">{{ $invoice->invoice_number }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">تاريخ الإصدار:</span>
                            <span class="font-medium">{{ $invoice->issue_date->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">تاريخ الاستحقاق:</span>
                            <span class="font-medium {{ $invoice->status == 'overdue' ? 'text-red-600' : '' }}">
                            {{ $invoice->due_date->translatedFormat('d M Y') }}
                                @if($invoice->status == 'overdue')
                                    <br><span class="text-sm">(متأخرة {{ $invoice->due_date->diffInDays(now()) }} يوم)</span>
                                @endif
                        </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">شروط الدفع:</span>
                            <span class="font-medium">30 يوم صافي</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">#</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الوصف</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الكمية</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">سعر الوحدة</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الإجمالي</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @foreach($invoice->items as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-800">{{ $item->description }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ number_format($item->quantity, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ number_format($item->unit_price, 2) }} ر.س</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ number_format($item->total, 2) }} ر.س</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3"></td>
                        <td class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الإجمالي الفرعي:</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-800">{{ number_format($invoice->subtotal, 2) }} ر.س</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الضريبة (15%):</td>
                        <td class="px-6 py-4 text-sm font-bold text-red-600">{{ number_format($invoice->tax, 2) }} ر.س</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td class="px-6 py-4 text-right text-lg font-semibold text-gray-800">الإجمالي النهائي:</td>
                        <td class="px-6 py-4 text-2xl font-bold text-indigo-600">{{ number_format($invoice->total, 2) }} ر.س</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">المبلغ الإجمالي</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($invoice->total, 2) }} ر.س</p>
                    </div>
                    <div class="bg-indigo-100 p-3 rounded-lg">
                        <i class="fas fa-file-invoice-dollar text-xl text-indigo-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">المبلغ المدفوع</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($totalPaid, 2) }} ر.س</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-hand-holding-usd text-xl text-green-600"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $totalPaid > 0 ? ($totalPaid / $invoice->total * 100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-600 mt-2 text-center">{{ round($totalPaid / $invoice->total * 100, 1) }}% مدفوع</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">المبلغ المتبقي</p>
                        <p class="text-2xl font-bold text-red-600">{{ number_format($remaining, 2) }} ر.س</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-lg">
                        <i class="fas fa-clock text-xl text-red-600"></i>
                    </div>
                </div>
                <div class="mt-4">
                    @if($remaining > 0)
                        <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}" class="block w-full text-center bg-red-600 text-white py-2.5 rounded-lg hover:bg-red-700 font-medium">
                            <i class="fas fa-hand-holding-usd ml-2"></i>
                            تسديد الدفعة
                        </a>
                    @else
                        <div class="text-center text-green-600 font-medium">
                            <i class="fas fa-check-circle ml-2"></i>
                            تم تسديد المبلغ بالكامل
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payments History -->
        @if($invoice->payments->count() > 0)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-history text-indigo-600 ml-2"></i>
                        سجل المدفوعات
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الدفعة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">طريقة الدفع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم المرجع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ملاحظات</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($invoice->payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $payment->payment_date->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $payment->payment_method }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $payment->reference ?? 'لا يوجد' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                    {{ number_format($payment->amount, 2) }} ر.س
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $payment->notes ?? 'لا يوجد' }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Notes -->
        @if($invoice->notes)
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-sticky-note text-yellow-600 ml-2"></i>
                    ملاحظات
                </h3>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-gray-800">{{ $invoice->notes }}</p>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">إجراءات الفاتورة</h3>
                    <p class="text-sm text-gray-600">خيارات متاحة لهذه الفاتورة</p>
                </div>
                <div class="flex space-x-3 space-x-reverse">
                    @if($invoice->status == 'draft')
                        <button onclick="sendInvoice({{ $invoice->id }})" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 font-medium">
                            <i class="fas fa-paper-plane ml-2"></i>
                            إرسال الفاتورة
                        </button>
                        <a href="{{ route('invoices.edit', $invoice) }}" class="bg-green-600 text-white px-6 py-2.5 rounded-lg hover:bg-green-700 font-medium">
                            <i class="fas fa-edit ml-2"></i>
                            تعديل الفاتورة
                        </a>
                        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفاتورة؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-6 py-2.5 rounded-lg hover:bg-red-700 font-medium">
                                <i class="fas fa-trash ml-2"></i>
                                حذف الفاتورة
                            </button>
                        </form>
                    @endif

                    @if($invoice->status == 'sent')
                        <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}" class="bg-green-600 text-white px-6 py-2.5 rounded-lg hover:bg-green-700 font-medium">
                            <i class="fas fa-hand-holding-usd ml-2"></i>
                            تسديد دفعة
                        </a>
                    @endif

                    <button onclick="printInvoice()" class="bg-gray-600 text-white px-6 py-2.5 rounded-lg hover:bg-gray-700 font-medium">
                        <i class="fas fa-print ml-2"></i>
                        طباعة
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function printInvoice() {
            window.print();
        }

        function sendInvoice(invoiceId) {
            if (confirm('هل تريد إرسال هذه الفاتورة إلى العميل؟')) {
                fetch(`/invoices/${invoiceId}/send`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('تم إرسال الفاتورة بنجاح', 'success');
                            setTimeout(() => location.reload(), 1500);
                        }
                    })
                    .catch(error => {
                        showNotification('حدث خطأ أثناء إرسال الفاتورة', 'error');
                    });
            }
        }
    </script>

    <style>
        @media print {
            nav, footer, .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .container {
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }
    </style>
@endpush
