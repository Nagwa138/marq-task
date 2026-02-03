@extends('layouts.app')

@section('title', 'إنشاء فاتورة جديدة')
@section('icon', 'fa-file-invoice-dollar')
@section('subtitle', 'أنشئ فاتورة جديدة لعملائك')

@section('actions')
    <button type="button" onclick="saveAsDraft()" class="bg-gray-600 text-white px-4 py-3 rounded-lg hover:bg-gray-700">
        <i class="fas fa-save ml-2"></i> حفظ كمسودة
    </button>
    <button type="submit" form="invoiceForm" class="btn-primary">
        <i class="fas fa-check-circle ml-2"></i> حفظ وإرسال
    </button>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Invoice Form -->
        <form id="invoiceForm" action="{{ route('invoices.store') }}" method="POST" class="bg-white rounded-xl shadow-sm p-6">
            @csrf

            <div class="space-y-8">
                <!-- Header -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Customer Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user ml-2"></i> العميل *
                        </label>
                        <select name="customer_id"
                                required
                                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 select2">
                            <option value="">اختر عميل</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} - {{ $customer->phone ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt ml-2"></i> تاريخ الإصدار *
                            </label>
                            <input type="date"
                                   name="issue_date"
                                   required
                                   value="{{ old('issue_date', date('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-times ml-2"></i> تاريخ الاستحقاق *
                            </label>
                            <input type="date"
                                   name="due_date"
                                   required
                                   value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}"
                                   class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Invoice Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-hashtag ml-2"></i> رقم الفاتورة
                        </label>
                        <input type="text"
                               name="invoice_number"
                               value="{{ old('invoice_number', \App\Models\Invoice::generateInvoiceNumber()) }}"
                               class="w-full px-4 py-3 border rounded-lg bg-gray-50" readonly>
                        <p class="text-xs text-gray-500 mt-1">يتم توليده تلقائياً</p>
                    </div>
                </div>

                <!-- Items Table -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-list ml-2"></i> بنود الفاتورة
                        </h3>
                        <button type="button"
                                onclick="addInvoiceItem()"
                                class="bg-indigo-100 text-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-200">
                            <i class="fas fa-plus ml-2"></i> إضافة بند
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">#</th>
                                <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">الوصف *</th>
                                <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">الكمية *</th>
                                <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">سعر الوحدة *</th>
                                <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">الإجمالي</th>
                                <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">إجراء</th>
                            </tr>
                            </thead>
                            <tbody id="invoice-items" class="bg-white divide-y divide-gray-200">
                            <!-- Default First Item -->
                            <tr class="invoice-item border-b">
                                <td class="px-4 py-3 text-center">1</td>
                                <td class="px-4 py-3">
                                    <input type="text"
                                           name="items[0][description]"
                                           class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 item-description"
                                           placeholder="وصف البند" required>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number"
                                           name="items[0][quantity]"
                                           class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 item-quantity"
                                           value="1" min="1" step="1" onchange="calculateInvoiceTotal()" required>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number"
                                           name="items[0][unit_price]"
                                           class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 item-price"
                                           value="0" min="0" step="0.01" onchange="calculateInvoiceTotal()" required>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text"
                                           class="w-full px-3 py-2 border rounded bg-gray-50 item-total"
                                           value="0.00" readonly>
                                </td>
                                <td class="px-4 py-3">
                                    <button type="button"
                                            class="text-red-600 hover:text-red-800"
                                            onclick="removeInvoiceItem(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Totals Section -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Discount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">التخفيض</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number"
                                       name="discount"
                                       id="discount"
                                       value="{{ old('discount', 0) }}"
                                       min="0"
                                       step="0.01"
                                       onchange="calculateInvoiceTotal()"
                                       class="px-4 py-3 border rounded-lg">
                                <select name="discount_type"
                                        id="discount_type"
                                        onchange="calculateInvoiceTotal()"
                                        class="px-4 py-3 border rounded-lg">
                                    <option value="fixed">ثابت</option>
                                    <option value="percentage">نسبة مئوية</option>
                                </select>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                            <textarea name="notes"
                                      rows="3"
                                      class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500"
                                      placeholder="ملاحظات إضافية للفاتورة">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Totals Display -->
                        <div class="bg-white rounded-lg p-4 border">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">المجموع الفرعي:</span>
                                    <span id="subtotal" class="font-medium">0.00 ر.س</span>
                                    <input type="hidden" name="subtotal" value="0">
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">التخفيض:</span>
                                    <span id="discount-display" class="font-medium text-red-600">0.00 ر.س</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">الضريبة (15%):</span>
                                    <span id="tax" class="font-medium">0.00 ر.س</span>
                                    <input type="hidden" name="tax" value="0">
                                </div>

                                <div class="border-t pt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-bold text-gray-800">الإجمالي النهائي:</span>
                                        <span id="total" class="text-2xl font-bold text-indigo-600">0.00 ر.س</span>
                                        <input type="hidden" name="total" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Terms -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-credit-card ml-2"></i> شروط الدفع
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @foreach(['net_30' => '30 يوم', 'net_15' => '15 يوم', 'due_on_receipt' => 'عند الاستلام', 'custom' => 'مخصص'] as $value => $label)
                            <label class="flex items-center p-4 border rounded-lg hover:border-indigo-300 cursor-pointer">
                                <input type="radio"
                                       name="payment_terms"
                                       value="{{ $value }}"
                                       class="h-5 w-5 text-indigo-600"
                                    {{ old('payment_terms', 'net_30') == $value ? 'checked' : '' }}>
                                <span class="mr-3">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 space-x-reverse pt-6 border-t">
                    <a href="{{ route('invoices.index') }}" class="px-6 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-times ml-2"></i> إلغاء
                    </a>
                    <button type="button" onclick="previewInvoice()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-eye ml-2"></i> معاينة
                    </button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane ml-2"></i> حفظ وإرسال الفاتورة
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-eye text-indigo-600 ml-2"></i>
                        معاينة الفاتورة
                    </h3>
                    <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Preview Content -->
                <div class="p-6" id="previewContent">
                    <!-- Preview will be loaded here -->
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t flex justify-end space-x-3 space-x-reverse">
                    <button onclick="printPreview()" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        <i class="fas fa-print ml-2"></i> طباعة
                    </button>
                    <button onclick="saveAsDraft()" class="px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                        <i class="fas fa-save ml-2"></i> حفظ كمسودة
                    </button>
                    <button onclick="submitForm()" class="btn-primary">
                        <i class="fas fa-paper-plane ml-2"></i> إرسال الفاتورة
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .invoice-item input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Initialize Select2
        $(document).ready(function() {
            $('.select2').select2({
                dir: "rtl",
                placeholder: "اختر عميل",
                allowClear: true
            });

            // Calculate initial total
            calculateInvoiceTotal();
        });

        // Save as draft
        function saveAsDraft() {
            const form = $('#invoiceForm');
            form.append('<input type="hidden" name="status" value="draft">');
            form.submit();
        }

        // Preview invoice
        function previewInvoice() {
            // Collect form data
            const formData = new FormData(document.getElementById('invoiceForm'));

            // AJAX request to generate preview
            $.ajax({
                url: '{{ route("invoices.preview") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#previewContent').html(response.html);
                    $('#previewModal').removeClass('hidden');
                }
            });
        }

        // Close preview
        function closePreview() {
            $('#previewModal').addClass('hidden');
        }

        // Print preview
        function printPreview() {
            const printContent = document.getElementById('previewContent').innerHTML;
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        }

        // Submit form
        function submitForm() {
            document.getElementById('invoiceForm').submit();
        }
    </script>
@endpush
