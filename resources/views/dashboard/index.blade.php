{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- إحصائيات -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700">إجمالي الفواتير</h3>
                <p class="text-3xl font-bold text-indigo-600">{{ $stats['total_invoices'] }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700">عدد العملاء</h3>
                <p class="text-3xl font-bold text-green-600">{{ $stats['total_customers'] }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700">إجمالي الإيرادات</h3>
                <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['revenue'], 2) }} ر.س</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700">فواتير معلقة</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_invoices'] }}</p>
            </div>
        </div>

        <!-- الفواتير الحديثة -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">أحدث الفواتير</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <tr>
                        <th class="px-6 py-3 text-right">رقم الفاتورة</th>
                        <th class="px-6 py-3 text-right">العميل</th>
                        <th class="px-6 py-3 text-right">التاريخ</th>
                        <th class="px-6 py-3 text-right">المبلغ</th>
                        <th class="px-6 py-3 text-right">الحالة</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($recent_invoices as $invoice)
                        <tr>
                            <td class="px-6 py-4">{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-4">{{ $invoice->customer->name }}</td>
                            <td class="px-6 py-4">{{ $invoice->issue_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">{{ number_format($invoice->total, 2) }}</td>
                            <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs {{
                                $invoice->status == 'paid' ? 'bg-green-100 text-green-800' :
                                ($invoice->status == 'sent' ? 'bg-yellow-100 text-yellow-800' :
                                'bg-gray-100 text-gray-800')
                            }}">
                                {{ $invoice->status }}
                            </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
