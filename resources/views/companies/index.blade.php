@extends('layouts.app')

@section('title', 'إدارة الشركات')
@section('icon', 'fa-building')
@section('subtitle', 'أدارة جميع الشركات في النظام')

@section('actions')
    <a href="{{ route('companies.create') }}" class="btn-primary">
        <i class="fas fa-plus ml-2"></i> إضافة شركة
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow-sm">
        <!-- Filter Section -->
        <div class="p-6 border-b">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <input type="text"
                               id="searchInput"
                               placeholder="ابحث عن شركة بالاسم أو البريد..."
                               class="w-full px-4 py-3 pr-12 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                        <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <select id="statusFilter" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                        <option value="">كل الحالات</option>
                        <option value="active">نشطة</option>
                        <option value="inactive">غير نشطة</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <select id="sortFilter" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                        <option value="newest">الأحدث أولاً</option>
                        <option value="oldest">الأقدم أولاً</option>
                        <option value="name">حسب الاسم</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <button onclick="applyFilters()" class="btn-primary">
                        <i class="fas fa-filter ml-2"></i> تطبيق
                    </button>
                    <button onclick="resetFilters()" class="bg-gray-100 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-200">
                        <i class="fas fa-redo ml-2"></i> إعادة تعيين
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <!-- Stats Cards -->
        <div class="p-6 border-b">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Active Companies Card -->
                <div class="card bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-xl hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-800">الشركات النشطة</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $stats['active_companies'] }}</p>
                            @if($stats['active_companies'] > 0)
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="fas fa-building ml-1"></i>
                                    {{ $companies->count() }} إجمالي الشركات
                                </p>
                            @else
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="fas fa-info-circle ml-1"></i>
                                    أضف شركتك الأولى
                                </p>
                            @endif
                        </div>
                        <i class="fas fa-building text-3xl text-blue-600 opacity-50"></i>
                    </div>
                </div>

                <!-- Monthly Invoices Card -->
                <div class="card bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-xl hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-800">فواتير الشهر</p>
                            <p class="text-2xl font-bold text-green-900">{{ $stats['monthly_invoices'] }}</p>
                            <div class="flex items-center space-x-2 space-x-reverse mt-1">
                                @if($stats['paid_invoices'] > 0)
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                <i class="fas fa-check-circle ml-1"></i>
                                {{ $stats['paid_invoices'] }} مدفوعة
                            </span>
                                @endif
                                @if($stats['overdue_invoices'] > 0)
                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">
                                <i class="fas fa-exclamation-triangle ml-1"></i>
                                {{ $stats['overdue_invoices'] }} متأخرة
                            </span>
                                @endif
                            </div>
                        </div>
                        <i class="fas fa-file-invoice text-3xl text-green-600 opacity-50"></i>
                    </div>
                </div>

                <!-- Total Revenue Card -->
                <div class="card bg-gradient-to-r from-purple-50 to-purple-100 p-6 rounded-xl hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-800">إجمالي الإيرادات</p>
                            <p class="text-2xl font-bold text-purple-900">{{ $stats['formatted_revenue'] }} ر.س</p>
                            <div class="mt-1">
                                @if($stats['revenue_growth'] > 0)
                                    <span class="text-xs text-green-600">
                                <i class="fas fa-arrow-up ml-1"></i>
                                +{{ $stats['revenue_growth'] }}% عن الشهر الماضي
                            </span>
                                @elseif($stats['revenue_growth'] < 0)
                                    <span class="text-xs text-red-600">
                                <i class="fas fa-arrow-down ml-1"></i>
                                {{ abs($stats['revenue_growth']) }}% عن الشهر الماضي
                            </span>
                                @else
                                    <span class="text-xs text-gray-600">
                                <i class="fas fa-minus ml-1"></i>
                                لا يوجد تغيير
                            </span>
                                @endif
                            </div>
                        </div>
                        <i class="fas fa-money-bill-wave text-3xl text-purple-600 opacity-50"></i>
                    </div>
                </div>

                <!-- New Customers Card -->
                <div class="card bg-gradient-to-r from-yellow-50 to-yellow-100 p-6 rounded-xl hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-yellow-800">عملاء جدد</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $stats['new_customers'] }}</p>
                            <p class="text-xs text-yellow-600 mt-1">
                                <i class="fas fa-users ml-1"></i>
                                {{ $stats['total_customers'] }} إجمالي العملاء
                            </p>
                        </div>
                        <i class="fas fa-user-plus text-3xl text-yellow-600 opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Additional Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <!-- Total Invoices -->
                <div class="bg-white border rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg ml-3">
                            <i class="fas fa-file-invoice text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">إجمالي الفواتير</p>
                            <p class="text-lg font-bold text-gray-800">{{ $stats['total_invoices'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Paid -->
                <div class="bg-white border rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg ml-3">
                            <i class="fas fa-hand-holding-usd text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">إجمالي المدفوعات</p>
                            <p class="text-lg font-bold text-green-600">{{ number_format($stats['total_paid'], 0) }} ر.س</p>
                        </div>
                    </div>
                </div>

                <!-- Outstanding Balance -->
                <div class="bg-white border rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="bg-red-100 p-2 rounded-lg ml-3">
                            <i class="fas fa-clock text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">الرصيد المستحق</p>
                            <p class="text-lg font-bold text-red-600">{{ number_format($stats['outstanding_balance'], 0) }} ر.س</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Companies Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 data-table">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الشعار</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">اسم الشركة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المعلومات</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإحصائيات</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($paginatedCompanies as $company)
                    <tr class="table-row-hover">
                        <!-- Logo -->
                        <td class="px-6 py-4">
                            <div class="h-12 w-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                                @if($company->logo)
                                    <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="h-12 w-12 rounded-lg object-cover">
                                @else
                                    <i class="fas fa-building text-2xl text-indigo-600"></i>
                                @endif
                            </div>
                        </td>

                        <!-- Company Info -->
                        <td class="px-6 py-4">
                            <div>
                                <a href="{{ route('companies.show', $company) }}" class="font-medium text-gray-900 hover:text-indigo-600">
                                    {{ $company->name }}
                                </a>
                                <p class="text-sm text-gray-500 mt-1">{{ $company->email }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $company->tax_number ?? 'بدون رقم ضريبي' }}</p>
                            </div>
                        </td>

                        <!-- Contact Info -->
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-phone ml-2 text-gray-400"></i>
                                    {{ $company->phone ?? 'N/A' }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt ml-2 text-gray-400"></i>
                                    <span class="truncate max-w-xs">{{ $company->address ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Stats -->
                        <td class="px-6 py-4">
                            <div class="space-y-2">
                                <div>
                                    <p class="text-xs text-gray-500">الفواتير</p>
                                    <p class="text-sm font-medium">{{ $company->invoices_count ?? 0 }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">العملاء</p>
                                    <p class="text-sm font-medium">{{ $company->customers_count ?? 0 }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $company->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $company->is_active ? 'نشطة' : 'غير نشطة' }}
                        </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <!-- View -->
                                <a href="{{ route('companies.show', $company) }}"
                                   class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50"
                                   title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Edit -->
                                <a href="{{ route('companies.edit', $company) }}"
                                   class="text-yellow-600 hover:text-yellow-900 p-2 rounded-lg hover:bg-yellow-50"
                                   title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Invoices -->
                                <a href="{{ route('invoices.index') }}?company={{ $company->id }}"
                                   class="text-purple-600 hover:text-purple-900 p-2 rounded-lg hover:bg-purple-50"
                                   title="فواتير الشركة">
                                    <i class="fas fa-file-invoice"></i>
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('companies.destroy', $company) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirmDelete('هل تريد حذف هذه الشركة وجميع بياناتها؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50"
                                            title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach

                @if($paginatedCompanies->isEmpty())
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-building text-4xl mb-4"></i>
                                <p class="text-lg">لا توجد شركات مسجلة بعد</p>
                                <p class="text-sm mt-2">ابدأ بإضافة شركتك الأولى</p>
                                <a href="{{ route('companies.create') }}" class="btn-primary inline-block mt-4">
                                    <i class="fas fa-plus ml-2"></i> إضافة شركة
                                </a>
                            </div>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($paginatedCompanies->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $paginatedCompanies->links() }}
            </div>
        @endif
    </div>

    <!-- Quick Add Modal -->
    <div id="quickAddModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-plus-circle text-indigo-600 ml-2"></i>
                        إضافة شركة سريعة
                    </h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <form id="quickAddForm">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">اسم الشركة *</label>
                                <input type="text" name="name" class="w-full px-4 py-3 border rounded-lg" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                                <input type="email" name="email" class="w-full px-4 py-3 border rounded-lg">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                                    <input type="tel" name="phone" class="w-full px-4 py-3 border rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الضريبي</label>
                                    <input type="text" name="tax_number" class="w-full px-4 py-3 border rounded-lg">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t flex justify-end space-x-3 space-x-reverse">
                    <button onclick="closeModal()" class="px-6 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        إلغاء
                    </button>
                    <button onclick="submitQuickAdd()" class="btn-primary">
                        <i class="fas fa-save ml-2"></i> حفظ
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function applyFilters() {
            const search = $('#searchInput').val();
            const status = $('#statusFilter').val();
            const sort = $('#sortFilter').val();

            // Implement filter logic
            console.log('Applying filters:', { search, status, sort });

            // You can use DataTables filtering or reload page with query params
            window.location.href = `{{ route('companies.index') }}?search=${search}&status=${status}&sort=${sort}`;
        }

        function resetFilters() {
            $('#searchInput').val('');
            $('#statusFilter').val('');
            $('#sortFilter').val('newest');
            applyFilters();
        }

        function openQuickAddModal() {
            $('#quickAddModal').removeClass('hidden');
        }

        function closeModal() {
            $('#quickAddModal').addClass('hidden');
        }

        function submitQuickAdd() {
            const form = $('#quickAddForm');
            const formData = form.serialize();

            // Submit via AJAX
            $.ajax({
                url: '{{ route("companies.store") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        closeModal();
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('حدث خطأ أثناء الإضافة');
                }
            });
        }

        // Initialize
        $(document).ready(function() {
            // Auto-focus search
            $('#searchInput').focus();

            // Initialize tooltips
            $('[title]').tooltip();

            // Export functionality
            $('#exportBtn').click(function() {
                {{--window.open('{{ route("companies.export") }}', '_blank');--}}
            });
        });
    </script>
@endpush
