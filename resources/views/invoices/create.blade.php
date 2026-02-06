@extends('layouts.app')

@section('title', 'إنشاء فاتورة جديدة - النظام المحاسبي')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-file-invoice-dollar text-indigo-600 ml-2"></i>
                        إنشاء فاتورة جديدة
                    </h1>
                    <p class="text-gray-600 mt-2">إنشاء فاتورة جديدة للشركة النشطة</p>
                </div>
                <div>
                    <a href="{{ route('invoices.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                        <i class="fas fa-arrow-right ml-1"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
            @csrf

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-600">{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Basic Information -->
                <div class="lg:col-span-2">
                    <!-- Company Selection -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <div class="flex items-center mb-6">
                            <div class="bg-indigo-100 p-2 rounded-lg ml-3">
                                <i class="fas fa-building text-indigo-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">اختر الشركة</h3>
                        </div>

                        @error('company_id')
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-600">{{ $message }}</p>
                        </div>
                        @enderror

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Active Company Card -->
                                <div class="company-card bg-white rounded-xl shadow-sm border p-6 active tenant-badge cursor-pointer"
                                     onclick="selectCompany({{ $customer->company_id }})">
                                    <div class="flex items-center">
                                        @if($customer->company->logo)
                                            <img src="{{ asset('/storage/' . $customer->company->logo) }}" alt="{{ $customer->company->name }}" class="h-12 w-12 rounded-lg object-cover ml-3">
                                        @else
                                            <div class="h-12 w-12 rounded-lg bg-indigo-100 flex items-center justify-center ml-3">
                                                <i class="fas fa-building text-indigo-600"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="font-semibold text-gray-800">{{ $customer->company->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $customer->company->email }}</p>
                                        </div>
                                    </div>
                                    <input type="radio" name="company_id" value="{{ $customer->company_id }}" class="hidden" checked>
                                </div>
                        </div>
                    </div>
                    <input id="customer_id" name="customer_id" value="{{ $customer->id }}" type="hidden" />

                   <!-- Invoice Items -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="bg-purple-100 p-2 rounded-lg ml-3">
                                    <i class="fas fa-list text-purple-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">أصناف الفاتورة</h3>
                            </div>
                            <button type="button" onclick="addItem()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">
                                <i class="fas fa-plus ml-1"></i> إضافة صنف
                            </button>
                        </div>

                        <div id="itemsContainer">
                            <!-- Items will be added here dynamically -->
                            <div class="item-row mb-4 p-4 border rounded-lg">
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                                        <input type="text" name="items[0][description]" required
                                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 item-description"
                                               placeholder="وصف الصنف">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">الكمية</label>
                                        <input type="number" name="items[0][quantity]" step="0.01" min="0.01" value="1" required
                                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 item-quantity"
                                               onchange="calculateItemTotal(0)">
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">سعر الوحدة</label>
                                        <input type="number" name="items[0][unit_price]" step="0.01" min="0" value="0" required
                                               class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 item-unit-price"
                                               onchange="calculateItemTotal(0)">
                                    </div>
                                    <div class="col-span-1 flex items-end">
                                        <button type="button" onclick="removeItem(0)" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-2 text-right">
                                    <span class="text-sm text-gray-500">الإجمالي: </span>
                                    <span class="font-bold item-total">0.00 ر.س</span>
                                </div>
                            </div>
                        </div>

                        <!-- Items Error -->
                        @error('items')
                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-600">{{ $message }}</p>
                        </div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-yellow-100 p-2 rounded-lg ml-3">
                                <i class="fas fa-sticky-note text-yellow-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">ملاحظات</h3>
                        </div>
                        <textarea name="notes" rows="4"
                                  class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="أضف ملاحظات حول الفاتورة (اختياري)">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Right Column - Invoice Details & Summary -->
                <div class="lg:col-span-1">
                    <!-- Invoice Details -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <div class="flex items-center mb-6">
                            <div class="bg-green-100 p-2 rounded-lg ml-3">
                                <i class="fas fa-info-circle text-green-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">تفاصيل الفاتورة</h3>
                        </div>

                        <!-- Invoice Number -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">رقم الفاتورة</label>
                            <input type="text" name="invoice_number" value="{{ \App\Architecture\Repositories\Classes\InvoiceRepository::generateInvoiceNumber() }}" readonly
                                   class="w-full bg-gray-50 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Issue Date -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                تاريخ الإصدار <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('issue_date') border-red-500 @enderror">
                            @error('issue_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Due Date -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                تاريخ الاستحقاق <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('due_date') border-red-500 @enderror">
                            @error('due_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Terms -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">شروط الدفع</label>
                            <select class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500" onchange="updateDueDate(this.value)">
                                <option value="30">30 يوم صافي</option>
                                <option value="15">15 يوم صافي</option>
                                <option value="7">7 أيام صافي</option>
                                <option value="0">نقداً عند التسليم</option>
                                <option value="custom">مخصص</option>
                            </select>
                        </div>
                    </div>

                    <!-- Invoice Summary -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">ملخص الفاتورة</h3>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">الإجمالي الفرعي</span>
                                <span class="font-medium" id="subtotal">0.00 ر.س</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">الضريبة (15%)</span>
                                <span class="font-medium text-red-600" id="tax">0.00 ر.س</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold text-gray-800">الإجمالي النهائي</span>
                                    <span class="text-2xl font-bold text-indigo-600" id="total">0.00 ر.س</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">حالة الفاتورة</label>
                            <div class="bg-gray-100 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center ml-3">
                                        <i class="fas fa-pen text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">مسودة</p>
                                        <p class="text-sm text-gray-500">ستكون الفاتورة بحالة مسودة حتى يتم إرسالها</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="space-y-4">
                            <button type="submit" name="action" value="save" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-medium">
                                <i class="fas fa-save ml-2"></i>
                                حفظ كمسودة
                            </button>
                            <button type="submit" name="action" value="save_and_send" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 font-medium">
                                <i class="fas fa-paper-plane ml-2"></i>
                                حفظ وإرسال
                            </button>
                            <a href="{{ route('invoices.index') }}" class="block w-full text-center bg-gray-200 text-gray-700 py-3 rounded-lg hover:bg-gray-300 font-medium">
                                إلغاء
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        let itemCount = 1;

        // Company Selection
        function selectCompany(companyId) {
            document.querySelectorAll('.company-card').forEach(card => {
                card.classList.remove('active', 'tenant-badge');
                card.querySelector('input[type="radio"]').checked = false;
            });

            const selectedCard = document.querySelector(`.company-card input[value="${companyId}"]`).closest('.company-card');
            selectedCard.classList.add('active', 'tenant-badge');
            selectedCard.querySelector('input[type="radio"]').checked = true;
        }

        // Invoice Items Management
        function addItem() {
            const container = document.getElementById('itemsContainer');
            const newItem = document.createElement('div');
            newItem.className = 'item-row mb-4 p-4 border rounded-lg';
            newItem.innerHTML = `
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-6">
                    <input type="text" name="items[${itemCount}][description]" required
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 item-description"
                           placeholder="وصف الصنف">
                </div>
                <div class="col-span-2">
                    <input type="number" name="items[${itemCount}][quantity]" step="0.01" min="0.01" value="1" required
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 item-quantity"
                           onchange="calculateItemTotal(${itemCount})">
                </div>
                <div class="col-span-3">
                    <input type="number" name="items[${itemCount}][unit_price]" step="0.01" min="0" value="0" required
                           class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 item-unit-price"
                           onchange="calculateItemTotal(${itemCount})">
                </div>
                <div class="col-span-1">
                    <button type="button" onclick="removeItem(${itemCount})" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="mt-2 text-right">
                <span class="text-sm text-gray-500">الإجمالي: </span>
                <span class="font-bold item-total">0.00 ر.س</span>
            </div>
        `;
            container.appendChild(newItem);
            calculateItemTotal(itemCount);
            itemCount++;
        }

        function removeItem(index) {
            if (document.querySelectorAll('.item-row').length > 1) {
                const item = document.querySelector(`input[name="items[${index}][description]"]`).closest('.item-row');
                item.remove();
                calculateTotal();
            }
        }

        function calculateItemTotal(index) {
            const quantity = parseFloat(document.querySelector(`input[name="items[${index}][quantity]"]`).value) || 0;
            const unitPrice = parseFloat(document.querySelector(`input[name="items[${index}][unit_price]"]`).value) || 0;
            const total = quantity * unitPrice;

            document.querySelector(`input[name="items[${index}][description]"]`).closest('.item-row').querySelector('.item-total').textContent =
                total.toFixed(2) + ' ر.س';

            calculateTotal();
        }

        function calculateTotal() {
            let subtotal = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const totalText = row.querySelector('.item-total').textContent;
                const totalValue = parseFloat(totalText.replace(' ر.س', '')) || 0;
                subtotal += totalValue;
            });

            const tax = subtotal * 0.15;
            const total = subtotal + tax;

            document.getElementById('subtotal').textContent = subtotal.toFixed(2) + ' ر.س';
            document.getElementById('tax').textContent = tax.toFixed(2) + ' ر.س';
            document.getElementById('total').textContent = total.toFixed(2) + ' ر.س';
        }

        // Update Due Date based on payment terms
        function updateDueDate(days) {
            if (days !== 'custom') {
                const issueDate = document.querySelector('input[name="issue_date"]').value;
                if (issueDate) {
                    const date = new Date(issueDate);
                    date.setDate(date.getDate() + parseInt(days));
                    const dueDate = date.toISOString().split('T')[0];
                    document.querySelector('input[name="due_date"]').value = dueDate;
                }
            }
        }

        // Form Validation
        document.getElementById('invoiceForm').addEventListener('submit', function(e) {
            let valid = true;
            const customerId = document.getElementById('customer_id').value;
            const companySelected = document.querySelector('input[name="company_id"]:checked');

            if (!customerId) {
                showNotification('يرجى اختيار العميل', 'error');
                valid = false;
            }

            if (!companySelected) {
                showNotification('يرجى اختيار الشركة', 'error');
                valid = false;
            }

            // Check if at least one item has description and price
            let hasValidItems = false;
            document.querySelectorAll('.item-row').forEach(row => {
                const desc = row.querySelector('.item-description').value;
                const price = parseFloat(row.querySelector('.item-unit-price').value) || 0;
                if (desc && price > 0) {
                    hasValidItems = true;
                }
            });

            if (!hasValidItems) {
                showNotification('يرجى إضافة صنف واحد على الأقل مع سعر', 'error');
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            const checkedCompany = document.querySelector('input[name="company_id"]:checked');
            if (checkedCompany) {
                selectCompany(checkedCompany.value);
            }

            calculateTotal();

            // Trigger customer info if pre-selected
            const customerSelect = document.getElementById('customer_id');
            if (customerSelect.value) {
                customerSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endpush
