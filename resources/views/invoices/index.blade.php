@extends('layouts.app')

@section('title', 'إدارة الفواتير')
@section('icon', 'fa-file-invoice')
@section('subtitle', 'أدارة جميع الفواتير في النظام')

@section('actions')
    <a href="{{ route('invoices.create') }}" class="btn-primary">
        <i class="fas fa-plus ml-2"></i> فاتورة جديدة
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
                               placeholder="ابحث برقم الفاتورة أو اسم العميل..."
                               class="w-full px-4 py-3 pr-12 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                        <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <select id="statusFilter" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                        <option value="">كل الحالات</option>
                        <option value="draft">مسودة</option>
                        <option value="sent">مرسلة</option>
                        <option value="paid">مدفوعة</option>
                        <option value="overdue">متأخرة</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <select id="dateFilter" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                        <option value="">كل التواريخ</option>
                        <option value="today">اليوم</option>
                        <option value="this_week">هذا الأسبوع</option>
                        <option value="this_month">هذا الشهر</option>
                        <option value="last_month">الشهر الماضي</option>
                        <option value="this_year">هذه السنة</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <select id="sortFilter" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                        <option value="newest">الأحدث أولاً</option>
                        <option value="oldest">الأقدم أولاً</option>
                        <option value="due_date_asc">أقرب موعد استحقاق</option>
                        <option value="due_date_desc">أبعد موعد استحقاق</option>
                        <option value="amount_desc">الأعلى قيمة</option>
                        <option value="amount_asc">الأقل قيمة</option>
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
        <div class="p-6 border-b">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total Invoices Card -->
                <div class="card bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-xl hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-800">إجمالي الفواتير</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $stats['total'] }}</p>
                            <div class="mt-2">
                                <span class="text-xs text-blue-600">
                                    <i class="fas fa-calendar ml-1"></i>
                                    هذا الشهر: {{ $stats['this_month'] }}
                                </span>
                            </div>
                        </div>
                        <i class="fas fa-file-invoice text-3xl text-blue-600 opacity-50"></i>
                    </div>
                </div>

                <!-- Paid Invoices Card -->
                <div class="card bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-xl hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-800">الفواتير المدفوعة</p>
                            <p class="text-2xl font-bold text-green-900">{{ $stats['paid'] }}</p>
                            <p class="text-sm text-green-700 mt-2">
                                {{ number_format($stats['paid_amount'], 2) }} ر.س
                            </p>
                        </div>
                        <i class="fas fa-hand-holding-usd text-3xl text-green-600 opacity-50"></i>
                    </div>
                </div>

                <!-- Outstanding Card -->
                <div class="card bg-gradient-to-r from-yellow-50 to-yellow-100 p-6 rounded-xl hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-yellow-800">المستحق تحصيله</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $stats['outstanding'] }}</p>
                            <p class="text-sm text-yellow-700 mt-2">
                                {{ number_format($stats['outstanding_amount'], 2) }} ر.س
                            </p>
                        </div>
                        <i class="fas fa-clock text-3xl text-yellow-600 opacity-50"></i>
                    </div>
                </div>

                <!-- Overdue Card -->
                <div class="card bg-gradient-to-r from-red-50 to-red-100 p-6 rounded-xl hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-red-800">الفواتير المتأخرة</p>
                            <p class="text-2xl font-bold text-red-900">{{ $stats['overdue'] }}</p>
                            <p class="text-sm text-red-700 mt-2">
                                {{ number_format($stats['overdue_amount'], 2) }} ر.س
                            </p>
                        </div>
                        <i class="fas fa-exclamation-triangle text-3xl text-red-600 opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Quick Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <!-- Average Invoice Value -->
                <div class="bg-white border rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-2 rounded-lg ml-3">
                            <i class="fas fa-chart-line text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">متوسط قيمة الفاتورة</p>
                            <p class="text-lg font-bold text-purple-600">{{ number_format($stats['average_invoice'], 2) }} ر.س</p>
                        </div>
                    </div>
                </div>

                <!-- Tax Collected -->
                <div class="bg-white border rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg ml-3">
                            <i class="fas fa-percentage text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">إجمالي الضريبة</p>
                            <p class="text-lg font-bold text-blue-600">{{ number_format($stats['total_tax'], 2) }} ر.س</p>
                        </div>
                    </div>
                </div>

                <!-- Collection Rate -->
                <div class="bg-white border rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg ml-3">
                            <i class="fas fa-chart-pie text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">معدل التحصيل</p>
                            <p class="text-lg font-bold text-green-600">{{ $stats['collection_rate'] }}%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 data-table">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الفاتورة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العميل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التواريخ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($invoices as $invoice)
                    <tr class="table-row-hover {{ $invoice->status == 'overdue' ? 'bg-red-50' : '' }}">
                        <!-- Invoice Number -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-lg {{ $invoice->status == 'paid' ? 'bg-green-100' : ($invoice->status == 'overdue' ? 'bg-red-100' : 'bg-indigo-100') }} flex items-center justify-center ml-3">
                                    <i class="fas fa-file-invoice {{ $invoice->status == 'paid' ? 'text-green-600' : ($invoice->status == 'overdue' ? 'text-red-600' : 'text-indigo-600') }}"></i>
                                </div>
                                <div>
                                    <a href="{{ route('invoices.show', $invoice) }}"
                                       class="font-medium text-gray-900 hover:text-indigo-600">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if($invoice->company)
                                            {{ $invoice->company->name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </td>

                        <!-- Customer Info -->
                        <td class="px-6 py-4">
                            <div>
                                <a href="{{ route('customers.show', $invoice->customer) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                    {{ $invoice->customer->name }}
                                </a>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $invoice->customer->email ?? 'N/A' }}
                                </p>
                            </div>
                        </td>

                        <!-- Dates -->
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-calendar-day ml-2 text-gray-400"></i>
                                    الإصدار: {{ $invoice->issue_date->format('Y-m-d') }}
                                </div>
                                <div class="text-sm {{ $invoice->isOverdue() ? 'text-red-600' : 'text-gray-600' }}">
                                    <i class="fas fa-clock ml-2 {{ $invoice->isOverdue() ? 'text-red-400' : 'text-gray-400' }}"></i>
                                    الاستحقاق: {{ $invoice->due_date->format('Y-m-d') }}
                                </div>
                            </div>
                        </td>

                        <!-- Amount -->
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div>
                                    <p class="text-sm text-gray-500">الإجمالي</p>
                                    <p class="text-lg font-bold text-gray-900">{{ number_format($invoice->total, 2) }} ر.س</p>
                                </div>
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                        فرعي: {{ number_format($invoice->subtotal, 2) }}
                                    </span>
                                    @if($invoice->tax > 0)
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                            ضريبة: {{ number_format($invoice->tax, 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            <div class="flex flex-col space-y-2">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' :
                                       ($invoice->status == 'sent' ? 'bg-blue-100 text-blue-800' :
                                       ($invoice->status == 'overdue' ? 'bg-red-100 text-red-800' :
                                       'bg-yellow-100 text-yellow-800')) }}">
                                    {{ trans('invoices.status.' . $invoice->status) }}
                                </span>

                                @if($invoice->isOverdue())
                                    <span class="text-xs text-red-600">
                                        <i class="fas fa-exclamation-triangle ml-1"></i>
                                        تأخر {{ $invoice->days_overdue }} يوم
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <!-- View -->
                                <a href="{{ route('invoices.show', $invoice) }}"
                                   class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50"
                                   title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Edit -->
                                @if($invoice->status == 'draft')
                                    <a href="{{ route('invoices.edit', $invoice) }}"
                                       class="text-yellow-600 hover:text-yellow-900 p-2 rounded-lg hover:bg-yellow-50"
                                       title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif

                                <!-- Print -->
                                <a href="{{ route('invoices.print', $invoice) }}" target="_blank"
                                   class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-50"
                                   title="طباعة">
                                    <i class="fas fa-print"></i>
                                </a>

                                <!-- Send -->
                                @if($invoice->status == 'draft')
                                    <button onclick="sendInvoice({{ $invoice->id }})"
                                            class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50"
                                            title="إرسال">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                @endif

                                <!-- Duplicate -->
                                <button onclick="duplicateInvoice({{ $invoice->id }})"
                                        class="text-purple-600 hover:text-purple-900 p-2 rounded-lg hover:bg-purple-50"
                                        title="نسخ">
                                    <i class="fas fa-copy"></i>
                                </button>

                                <!-- Delete -->
                                @if($invoice->status == 'draft')
                                    <form action="{{ route('invoices.destroy', $invoice) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('هل تريد حذف هذه الفاتورة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50"
                                                title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach

                @if($invoices->isEmpty())
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-file-invoice text-4xl mb-4"></i>
                                <p class="text-lg">لا توجد فواتير بعد</p>
                                <p class="text-sm mt-2">ابدأ بإنشاء فاتورتك الأولى</p>
                                <a href="{{ route('invoices.create') }}" class="btn-primary inline-block mt-4">
                                    <i class="fas fa-plus ml-2"></i> فاتورة جديدة
                                </a>
                            </div>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($invoices->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

    <!-- Bulk Actions Modal -->
    <div id="bulkActionsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-tasks text-indigo-600 ml-2"></i>
                        إجراءات جماعية
                    </h3>
                    <div class="space-y-3">
                        <button onclick="bulkSendInvoices()" class="w-full text-right px-4 py-3 border rounded-lg hover:bg-blue-50 hover:border-blue-300">
                            <i class="fas fa-paper-plane text-blue-600 ml-2"></i>
                            إرسال الفواتير المحددة
                        </button>
                        <button onclick="bulkPrintInvoices()" class="w-full text-right px-4 py-3 border rounded-lg hover:bg-gray-50 hover:border-gray-300">
                            <i class="fas fa-print text-gray-600 ml-2"></i>
                            طباعة الفواتير المحددة
                        </button>
                        <button onclick="bulkExportInvoices()" class="w-full text-right px-4 py-3 border rounded-lg hover:bg-green-50 hover:border-green-300">
                            <i class="fas fa-file-export text-green-600 ml-2"></i>
                            تصدير الفواتير المحددة
                        </button>
                    </div>
                </div>
                <div class="p-6 border-t flex justify-end">
                    <button onclick="closeBulkModal()" class="px-6 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let selectedInvoices = [];

        function applyFilters() {
            const search = $('#searchInput').val();
            const status = $('#statusFilter').val();
            const date = $('#dateFilter').val();
            const sort = $('#sortFilter').val();

            let params = new URLSearchParams();
            if (search) params.append('search', search);
            if (status) params.append('status', status);
            if (date) params.append('date', date);
            if (sort) params.append('sort', sort);

            window.location.href = `{{ route('invoices.index') }}?${params.toString()}`;
        }

        function resetFilters() {
            $('#searchInput').val('');
            $('#statusFilter').val('');
            $('#dateFilter').val('');
            $('#sortFilter').val('newest');
            applyFilters();
        }

        function sendInvoice(invoiceId) {
            if (confirm('هل تريد إرسال هذه الفاتورة للعميل؟')) {
                $.ajax({
                    url: `{{ url('invoices') }}/${invoiceId}/send`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('حدث خطأ أثناء إرسال الفاتورة');
                    }
                });
            }
        }

        function duplicateInvoice(invoiceId) {
            if (confirm('هل تريد نسخ هذه الفاتورة؟')) {
                $.ajax({
                    url: `{{ url('invoices') }}/${invoiceId}/duplicate`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = `{{ url('invoices') }}/${response.invoice_id}/edit`;
                        }
                    },
                    error: function() {
                        alert('حدث خطأ أثناء نسخ الفاتورة');
                    }
                });
            }
        }

        function toggleSelectAll(checkbox) {
            const isChecked = $(checkbox).is(':checked');
            $('.invoice-checkbox').prop('checked', isChecked);

            selectedInvoices = [];
            if (isChecked) {
                $('.invoice-checkbox').each(function() {
                    selectedInvoices.push($(this).val());
                });
            }
            updateBulkButton();
        }

        function toggleInvoiceSelection(checkbox) {
            const invoiceId = $(checkbox).val();

            if ($(checkbox).is(':checked')) {
                if (!selectedInvoices.includes(invoiceId)) {
                    selectedInvoices.push(invoiceId);
                }
            } else {
                const index = selectedInvoices.indexOf(invoiceId);
                if (index > -1) {
                    selectedInvoices.splice(index, 1);
                }
            }

            // Update select all checkbox
            const totalCheckboxes = $('.invoice-checkbox').length;
            const checkedCount = $('.invoice-checkbox:checked').length;
            $('#selectAll').prop('checked', totalCheckboxes === checkedCount);

            updateBulkButton();
        }

        function updateBulkButton() {
            const bulkBtn = $('#bulkActionsBtn');
            if (selectedInvoices.length > 0) {
                bulkBtn.removeClass('hidden').text(`إجراءات (${selectedInvoices.length})`);
            } else {
                bulkBtn.addClass('hidden');
            }
        }

        function openBulkModal() {
            if (selectedInvoices.length > 0) {
                $('#bulkActionsModal').removeClass('hidden');
            }
        }

        function closeBulkModal() {
            $('#bulkActionsModal').addClass('hidden');
        }

        function bulkSendInvoices() {
            if (selectedInvoices.length === 0) return;

            if (confirm(`هل تريد إرسال ${selectedInvoices.length} فاتورة؟`)) {
                $.ajax({
                    url: '{{ route("invoices.bulk.send") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        invoices: selectedInvoices
                    },
                    success: function(response) {
                        if (response.success) {
                            closeBulkModal();
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('حدث خطأ أثناء إرسال الفواتير');
                    }
                });
            }
        }

        function bulkPrintInvoices() {
            if (selectedInvoices.length === 0) return;

            // Open print preview for each invoice
            selectedInvoices.forEach(invoiceId => {
                window.open(`{{ url('invoices') }}/${invoiceId}/print`, '_blank');
            });
            closeBulkModal();
        }

        function bulkExportInvoices() {
            if (selectedInvoices.length === 0) return;

            window.open(`{{ route("invoices.export") }}?ids=${selectedInvoices.join(',')}`, '_blank');
            closeBulkModal();
        }

        $(document).ready(function() {
            // Auto-focus search
            $('#searchInput').focus();

            // Initialize tooltips
            $('[title]').tooltip();

            // Check for overdue invoices highlight
            $('.table-row-hover').hover(
                function() {
                    $(this).addClass('bg-gray-50');
                },
                function() {
                    if (!$(this).hasClass('bg-red-50')) {
                        $(this).removeClass('bg-gray-50');
                    }
                }
            );
        });
    </script>
@endpush
