@extends('layouts.app')

@section('title', 'إدارة العملاء')
@section('icon', 'fa-users')
@section('subtitle', 'أدارة جميع عملاء الشركة')

@section('actions')
    <a href="{{ route('customers.create') }}" class="btn-primary">
        <i class="fas fa-user-plus ml-2"></i> إضافة عميل
    </a>
{{--    <button onclick="exportCustomers()" class="bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700">--}}
{{--        <i class="fas fa-file-export ml-2"></i> تصدير--}}
{{--    </button>--}}
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Customer Stats -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <div class="card bg-white p-6 rounded-xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">إجمالي العملاء</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_customers'] }}</p>
                    </div>
                    <i class="fas fa-users text-2xl text-blue-600 opacity-50"></i>
                </div>
            </div>

            <div class="card bg-white p-6 rounded-xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">مدينون</p>
                        <p class="text-2xl font-bold text-red-600">{{ $overdueCustomers }}</p>
                    </div>
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600 opacity-50"></i>
                </div>
            </div>

            <div class="card bg-white p-6 rounded-xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">إجمالي الديون</p>
                        <p class="text-2xl font-bold text-orange-600">{{ number_format($totalDebt, 2) }} ر.س</p>
                    </div>
                    <i class="fas fa-money-bill-wave text-2xl text-orange-600 opacity-50"></i>
                </div>
            </div>

            <div class="card bg-white p-6 rounded-xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">عملاء جدد</p>
                        <p class="text-2xl font-bold text-green-600">{{ $newCustomers }}</p>
                    </div>
                    <i class="fas fa-user-plus text-2xl text-green-600 opacity-50"></i>
                </div>
            </div>

            <div class="card bg-white p-6 rounded-xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">معدل التعامل</p>
                        <p class="text-2xl font-bold text-purple-600">4.8</p>
                    </div>
                    <i class="fas fa-star text-2xl text-purple-600 opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <input type="text"
                               placeholder="ابحث عن عميل بالاسم أو البريد أو الهاتف..."
                               class="w-full px-4 py-3 pr-12 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500"
                               value="{{ request('search') }}">
                        <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Type -->
                <div>
                    <select class="w-full px-4 py-3 border rounded-lg">
                        <option value="">كل الأنواع</option>
                        <option value="individual">أفراد</option>
                        <option value="company">شركات</option>
                    </select>
                </div>

                <!-- Balance -->
                <div>
                    <select class="w-full px-4 py-3 border rounded-lg">
                        <option value="">كل الأرصدة</option>
                        <option value="positive">مدينون</option>
                        <option value="negative">دائنون</option>
                        <option value="zero">صفر</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <button class="btn-primary">
                        <i class="fas fa-filter ml-2"></i> تطبيق
                    </button>
                    <button class="bg-gray-100 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-200">
                        <i class="fas fa-redo ml-2"></i> إعادة
                    </button>
                </div>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 data-table">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العميل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">معلومات التواصل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الفواتير</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الرصيد</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($customers as $customer)
                        <tr class="hover:bg-gray-50 {{ $customer->balance > 0 ? 'bg-red-50' : '' }}">
                            <!-- ID -->
                            <td class="px-6 py-4">
                                <span class="text-gray-500">#{{ $customer->id }}</span>
                            </td>
                            <!-- Customer Info -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center ml-3">
                                        <i class="fas fa-user text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('customers.show', $customer) }}"
                                           class="font-medium text-gray-900 hover:text-indigo-600">
                                            {{ $customer->name }}
                                        </a>
                                        <p class="text-sm text-gray-500">{{ $customer->type == 'company' ? 'شركة' : 'فرد' }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact Info -->
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-envelope ml-2 text-gray-400"></i>
                                        {{ $customer->email ?? 'N/A' }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-phone ml-2 text-gray-400"></i>
                                        {{ $customer->phone ?? 'N/A' }}
                                    </div>
                                </div>
                            </td>

                            <!-- Invoices Stats -->
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">الفواتير</span>
                                        <span class="text-sm font-medium">{{ $customer->invoices_count ?? 0 }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">المدفوعات</span>
                                        <span class="text-sm font-medium">{{ $customer->payments_count ?? 0 }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Balance -->
                            <td class="px-6 py-4">
                                <div class="{{ $customer->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    <p class="font-bold">{{ number_format($customer->balance, 2) }} ر.س</p>
                                    @if($customer->balance > 0)
                                        <p class="text-xs">مدين</p>
                                    @elseif($customer->balance < 0)
                                        <p class="text-xs">دائن</p>
                                    @else
                                        <p class="text-xs">متوازن</p>
                                    @endif
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <!-- View -->
                                    <a href="{{ route('customers.show', $customer) }}"
                                       class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Edit -->
                                    <a href="{{ route('customers.edit', $customer) }}"
                                       class="text-yellow-600 hover:text-yellow-900 p-2 rounded-lg hover:bg-yellow-50">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Invoices -->
                                    <a href="{{ route('invoices.create') }}?customer_id={{ $customer->id }}"
                                       class="text-purple-600 hover:text-purple-900 p-2 rounded-lg hover:bg-purple-50">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </a>

                                    <!-- Payment -->
                                    <a href="{{ route('payments.create') }}?customer_id={{ $customer->id }}"
                                       class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50">
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('customers.destroy', $customer) }}"
                                          method="POST"
                                          onsubmit="return confirmDelete()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if($customers->isEmpty())
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <p class="text-lg">لا توجد عملاء مسجلين بعد</p>
                                    <p class="text-sm mt-2">ابدأ بإضافة عميلك الأول</p>
                                    <a href="{{ route('customers.create') }}" class="btn-primary inline-block mt-4">
                                        <i class="fas fa-user-plus ml-2"></i> إضافة عميل
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($customers->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function exportCustomers() {
            window.open('{{ route("customers.export") }}', '_blank');
        }

        function sendBulkSMS() {
            // Implement bulk SMS functionality
            alert('سيتم إرسال رسائل SMS للعملاء المحددين');
        }

        $(document).ready(function() {
            // Initialize DataTable with custom options
            $('.data-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
                },
                pageLength: 25,
                responsive: true,
                order: [],
                dom: '<"flex justify-between items-center mb-4"<"flex"l><"flex"f>>rt<"flex justify-between items-center mt-4"<"flex"i><"flex"p>>'
            });
        });
    </script>
@endpush
