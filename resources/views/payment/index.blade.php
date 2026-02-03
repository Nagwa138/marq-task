{{-- resources/views/payments/index.blade.php --}}
@extends('layouts.app')

@section('title', 'إدارة المدفوعات')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- العنوان والأزرار -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">إدارة المدفوعات</h1>

            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('payments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus ml-2"></i> دفعة جديدة
                </a>
                <a href="{{ route('payments.quick-create') }}" class="btn btn-secondary">
                    <i class="fas fa-bolt ml-2"></i> دفعة سريعة
                </a>
                <a href="{{ route('payments.export') }}" class="btn btn-success">
                    <i class="fas fa-file-export ml-2"></i> تصدير
                </a>
            </div>
        </div>

        <!-- إحصائيات -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg ml-4">
                        <i class="fas fa-money-bill-wave text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">إجمالي المدفوعات</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['total_amount'], 2) }} ر.س</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg ml-4">
                        <i class="fas fa-list text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">عدد المدفوعات</h3>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['total_payments'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-3 rounded-lg ml-4">
                        <i class="fas fa-calendar-day text-yellow-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">مدفوعات اليوم</h3>
                        <p class="text-3xl font-bold text-yellow-600">{{ number_format($stats['today_payments'], 2) }} ر.س</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- فلترة -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-4 border-b">
                <form action="{{ route('payments.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" placeholder="ابحث برقم المرجع أو اسم العميل..."
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500"
                               value="{{ request('search') }}">
                    </div>

                    <div>
                        <input type="date" name="from_date"
                               class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500"
                               value="{{ request('from_date') }}">
                    </div>

                    <div>
                        <input type="date" name="to_date"
                               class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500"
                               value="{{ request('to_date') }}">
                    </div>

                    <div>
                        <select name="payment_method" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                            <option value="">كل طرق الدفع</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>نقدي</option>
                            <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                            <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>بطاقة ائتمان</option>
                            <option value="check" {{ request('payment_method') == 'check' ? 'selected' : '' }}>شيك</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search ml-2"></i> بحث
                        </button>
                        <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo ml-2"></i> إعادة تعيين
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- جدول المدفوعات -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المرجع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العميل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الفاتورة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الدفع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">طريقة الدفع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900">{{ $payment->reference }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $payment->customer->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $payment->customer->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($payment->invoice)
                                    <a href="{{ route('invoices.show', $payment->invoice) }}"
                                       class="text-indigo-600 hover:text-indigo-900">
                                        {{ $payment->invoice->invoice_number }}
                                    </a>
                                @else
                                    <span class="text-gray-500">بدون فاتورة</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                            <span class="font-bold text-green-600">
                                {{ number_format($payment->amount, 2) }} ر.س
                            </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $payment->payment_date->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                                {{ $payment->payment_method_name }}
                            </span>
                            </td>
                            <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs {{
                                $payment->status == 'completed' ? 'bg-green-100 text-green-800' :
                                ($payment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                'bg-red-100 text-red-800')
                            }}">
                                {{ $payment->status }}
                            </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2 space-x-reverse">
                                    <a href="{{ route('payments.show', $payment) }}"
                                       class="text-blue-600 hover:text-blue-900" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('payments.edit', $payment) }}"
                                       class="text-yellow-600 hover:text-yellow-900" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('payments.destroy', $payment) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الدفعة؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-money-bill-wave text-4xl mb-4"></i>
                                    <p class="text-lg">لا توجد مدفوعات مسجلة بعد</p>
                                    <p class="text-sm mt-2">ابدأ بتسجيل مدفوعاتك الآن</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- الترقيم -->
            @if($payments->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
