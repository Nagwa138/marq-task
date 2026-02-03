@extends('layouts.app')

@section('title', 'إضافة عميل جديد - النظام المحاسبي')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-user-plus text-indigo-600 ml-2"></i>
                        إضافة عميل جديد
                    </h1>
                    <p class="text-gray-600 mt-2">أضف عميلاً جديداً للشركة النشطة</p>
                </div>
                <div>
                    <a href="{{ route('customers.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                        <i class="fas fa-arrow-right ml-1"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-full">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="h-1 w-16 bg-indigo-600"></div>
                    <div class="flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-full">
                        <span class="font-bold">1</span>
                    </div>
                    <div class="h-1 w-16 bg-gray-300"></div>
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-600 rounded-full">
                        <span class="font-bold">2</span>
                    </div>
                    <div class="h-1 w-16 bg-gray-300"></div>
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-600 rounded-full">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>
            <div class="flex justify-between mt-2 text-sm">
                <span class="text-indigo-600 font-medium">اختر الشركة</span>
                <span class="text-indigo-600 font-medium">معلومات العميل</span>
                <span class="text-gray-500">معلومات إضافية</span>
                <span class="text-gray-500">إكمال</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <form action="{{ route('customers.store') }}" method="POST" id="customerForm">
                @csrf

                <!-- Step 1: Company Selection -->
                <div id="step1" class="p-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-indigo-100 p-2 rounded-lg ml-3">
                            <i class="fas fa-building text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">اختر الشركة</h3>
                            <p class="text-sm text-gray-600 mt-1">اختر الشركة التي سيرتبط بها العميل</p>
                        </div>
                    </div>

                    @error('company_id')
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-600">{{ $message }}</p>
                    </div>
                    @enderror

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            $companies = \App\Models\Company::where('tenant_id', auth()->user()->tenant_id)->get();
                            $activeCompanyId = session('active_company_id');
                        @endphp

                        @if($activeCompanyId && $companies->where('id', $activeCompanyId)->first())
                            <!-- Active Company Card -->
                            @php
                                $activeCompany = $companies->where('id', $activeCompanyId)->first();
                            @endphp
                            <div class="company-card bg-gradient-to-r from-indigo-50 to-indigo-100 border-2 border-indigo-300 rounded-xl p-6 active tenant-badge cursor-pointer hover:shadow-md transition-shadow"
                                 onclick="selectCompany({{ $activeCompanyId }})">
                                <div class="flex items-center">
                                    @if($activeCompany->logo)
                                        <img src="{{ $activeCompany->logo_url }}" alt="{{ $activeCompany->name }}" class="h-14 w-14 rounded-lg object-cover ml-3">
                                    @else
                                        <div class="h-14 w-14 rounded-lg bg-white flex items-center justify-center ml-3 shadow-sm">
                                            <i class="fas fa-building text-2xl text-indigo-600"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-800">{{ $activeCompany->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $activeCompany->email }}</p>
                                        <div class="flex items-center mt-2">
                                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                                <i class="fas fa-check ml-1"></i> نشطة حالياً
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <input type="radio" name="company_id" value="{{ $activeCompanyId }}" class="hidden" {{ old('company_id') == $activeCompanyId ? 'checked' : '' }}>
                            </div>
                        @endif

                        <!-- Other Companies -->
                        @foreach($companies->where('id', '!=', $activeCompanyId) as $company)
                            <div class="company-card bg-white border border-gray-300 rounded-xl p-6 cursor-pointer hover:border-indigo-300 hover:shadow-md transition-all"
                                 onclick="selectCompany({{ $company->id }})">
                                <div class="flex items-center">
                                    @if($company->logo)
                                        <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="h-14 w-14 rounded-lg object-cover ml-3">
                                    @else
                                        <div class="h-14 w-14 rounded-lg bg-gray-50 flex items-center justify-center ml-3">
                                            <i class="fas fa-building text-2xl text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $company->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $company->email }}</p>
                                    </div>
                                </div>
                                <input type="radio" name="company_id" value="{{ $company->id }}" class="hidden" {{ old('company_id') == $company->id ? 'checked' : '' }}>
                            </div>
                        @endforeach

                        <!-- Add New Company Option -->
                        <div class="company-card bg-gradient-to-r from-gray-50 to-gray-100 border-2 border-dashed border-gray-300 rounded-xl p-6 flex flex-col items-center justify-center hover:border-indigo-300 hover:from-indigo-50 transition-all cursor-pointer"
                             onclick="window.location.href='{{ route('companies.create') }}'">
                            <div class="text-center">
                                <div class="bg-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                                    <i class="fas fa-plus text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="font-semibold text-gray-700">إضافة شركة جديدة</h3>
                                <p class="text-xs text-gray-500 mt-1">أنشئ شركة جديدة أولاً</p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons for Step 1 -->
                    <div class="flex justify-end mt-8 pt-6 border-t">
                        <button type="button" onclick="nextStep()" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-medium">
                            <i class="fas fa-arrow-left ml-2"></i>
                            التالي: معلومات العميل
                        </button>
                    </div>
                </div>

                <!-- Step 2: Customer Information -->
                <div id="step2" class="p-8 hidden">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 p-2 rounded-lg ml-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">معلومات العميل الأساسية</h3>
                            <p class="text-sm text-gray-600 mt-1">أدخل المعلومات الأساسية للعميل</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم العميل <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
                                   placeholder="أدخل اسم العميل الكامل">
                            @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Customer Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                نوع العميل <span class="text-red-500">*</span>
                            </label>
                            <select id="type" name="type" required
                                    class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('type') border-red-500 @enderror"
                                    onchange="toggleCustomerTypeFields()">
                                <option value="">-- اختر النوع --</option>
                                <option value="individual" {{ old('type') == 'individual' ? 'selected' : '' }}>فرد</option>
                                <option value="company" {{ old('type') == 'company' ? 'selected' : '' }}>شركة</option>
                            </select>
                            @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                البريد الإلكتروني
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror"
                                   placeholder="example@domain.com">
                            @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الهاتف الرئيسي
                            </label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('phone') border-red-500 @enderror"
                                   placeholder="+966 5X XXX XXXX">
                            @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tax Number -->
                        <div>
                            <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-2">
                                الرقم الضريبي
                            </label>
                            <input type="text" id="tax_number" name="tax_number" value="{{ old('tax_number') }}"
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('tax_number') border-red-500 @enderror"
                                   placeholder="الرقم الضريبي للعميل">
                            @error('tax_number')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Commercial Register (for companies) -->
                        <div id="commercial_register_field" class="hidden">
                            <label for="commercial_register" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم السجل التجاري
                            </label>
                            <input type="text" id="commercial_register" name="commercial_register" value="{{ old('commercial_register') }}"
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('commercial_register') border-red-500 @enderror"
                                   placeholder="رقم السجل التجاري">
                            @error('commercial_register')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Person (for companies) -->
                        <div id="contact_person_field" class="hidden">
                            <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">
                                الشخص المسؤول
                            </label>
                            <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}"
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('contact_person') border-red-500 @enderror"
                                   placeholder="اسم الشخص المسؤول">
                            @error('contact_person')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mt-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            العنوان
                        </label>
                        <textarea id="address" name="address" rows="3"
                                  class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('address') border-red-500 @enderror"
                                  placeholder="عنوان العميل الكامل">{{ old('address') }}</textarea>
                        @error('address')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Navigation Buttons for Step 2 -->
                    <div class="flex justify-between mt-8 pt-6 border-t">
                        <button type="button" onclick="prevStep()" class="px-6 py-3 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-arrow-right ml-2"></i>
                            السابق
                        </button>
                        <button type="button" onclick="nextStep()" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-medium">
                            <i class="fas fa-arrow-left ml-2"></i>
                            التالي: معلومات إضافية
                        </button>
                    </div>
                </div>

                <!-- Step 3: Additional Information -->
                <div id="step3" class="p-8 hidden">
                    <div class="flex items-center mb-6">
                        <div class="bg-purple-100 p-2 rounded-lg ml-3">
                            <i class="fas fa-info-circle text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">معلومات إضافية</h3>
                            <p class="text-sm text-gray-600 mt-1">معلومات اختيارية للعميل</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Website -->
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                                الموقع الإلكتروني
                            </label>
                            <input type="url" id="website" name="website" value="{{ old('website') }}"
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('website') border-red-500 @enderror"
                                   placeholder="https://example.com">
                            @error('website')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Person Phone -->
                        <div id="contact_person_phone_field" class="hidden">
                            <label for="contact_person_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                هاتف الشخص المسؤول
                            </label>
                            <input type="tel" id="contact_person_phone" name="contact_person_phone" value="{{ old('contact_person_phone') }}"
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('contact_person_phone') border-red-500 @enderror"
                                   placeholder="+966 5X XXX XXXX">
                            @error('contact_person_phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Terms -->
                        <div>
                            <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-2">
                                شروط الدفع الافتراضية
                            </label>
                            <select id="payment_terms" name="payment_terms"
                                    class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('payment_terms') border-red-500 @enderror">
                                <option value="">-- اختر شروط الدفع --</option>
                                <option value="net_30" {{ old('payment_terms') == 'net_30' ? 'selected' : '' }}>30 يوم صافي</option>
                                <option value="net_15" {{ old('payment_terms') == 'net_15' ? 'selected' : '' }}>15 يوم صافي</option>
                                <option value="due_on_receipt" {{ old('payment_terms') == 'due_on_receipt' ? 'selected' : '' }}>نقداً عند الاستلام</option>
                                <option value="custom" {{ old('payment_terms') == 'custom' ? 'selected' : '' }}>مخصص</option>
                            </select>
                            @error('payment_terms')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Credit Limit -->
                        <div>
                            <label for="credit_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                الحد الائتماني (ر.س)
                            </label>
                            <input type="number" id="credit_limit" name="credit_limit" value="{{ old('credit_limit') }}" step="0.01" min="0"
                                   class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('credit_limit') border-red-500 @enderror"
                                   placeholder="0.00">
                            @error('credit_limit')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            ملاحظات
                        </label>
                        <textarea id="notes" name="notes" rows="4"
                                  class="w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('notes') border-red-500 @enderror"
                                  placeholder="ملاحظات إضافية عن العميل">{{ old('notes') }}</textarea>
                        @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Navigation Buttons for Step 3 -->
                    <div class="flex justify-between mt-8 pt-6 border-t">
                        <button type="button" onclick="prevStep()" class="px-6 py-3 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-arrow-right ml-2"></i>
                            السابق
                        </button>
                        <button type="button" onclick="nextStep()" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-medium">
                            <i class="fas fa-arrow-left ml-2"></i>
                            التالي: مراجعة المعلومات
                        </button>
                    </div>
                </div>

                <!-- Step 4: Review and Submit -->
                <div id="step4" class="p-8 hidden">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-100 p-2 rounded-lg ml-3">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">مراجعة المعلومات</h3>
                            <p class="text-sm text-gray-600 mt-1">راجع المعلومات قبل حفظ العميل</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Company Info -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-4">الشركة</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-building text-gray-400 ml-2"></i>
                                        <span id="review_company">--</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Basic Info -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-4">المعلومات الأساسية</h4>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-500">الاسم</p>
                                        <p class="font-medium" id="review_name">--</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">النوع</p>
                                        <p class="font-medium" id="review_type">--</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-4">معلومات الاتصال</h4>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-500">البريد الإلكتروني</p>
                                        <p class="font-medium" id="review_email">--</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">الهاتف</p>
                                        <p class="font-medium" id="review_phone">--</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-4">معلومات إضافية</h4>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-500">الرقم الضريبي</p>
                                        <p class="font-medium" id="review_tax_number">--</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">شروط الدفع</p>
                                        <p class="font-medium" id="review_payment_terms">--</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address & Notes -->
                        <div class="mt-6 pt-6 border-t">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-4">العنوان</h4>
                                    <p class="text-gray-600" id="review_address">--</p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-4">ملاحظات</h4>
                                    <p class="text-gray-600" id="review_notes">--</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-between pt-6 border-t">
                        <button type="button" onclick="prevStep()" class="px-6 py-3 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-arrow-right ml-2"></i>
                            السابق
                        </button>
                        <div class="flex space-x-3 space-x-reverse">
                            <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 font-medium">
                                <i class="fas fa-save ml-2"></i>
                                حفظ العميل
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .company-card {
            transition: all 0.3s ease;
        }

        .company-card:hover {
            transform: translateY(-2px);
        }

        .company-card.active {
            border-color: #4f46e5;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.1);
        }

        .step-progress {
            transition: all 0.3s ease;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let currentStep = 1;
        const companies = @json($companies->keyBy('id')->toArray());

        function selectCompany(companyId) {
            // Remove active class from all company cards
            document.querySelectorAll('.company-card').forEach(card => {
                card.classList.remove('active', 'tenant-badge');
                const radio = card.querySelector('input[type="radio"]');
                if (radio) radio.checked = false;
            });

            // Add active class to selected company card
            const selectedCard = document.querySelector(`.company-card input[value="${companyId}"]`)?.closest('.company-card');
            if (selectedCard) {
                selectedCard.classList.add('active', 'tenant-badge');
                selectedCard.querySelector('input[type="radio"]').checked = true;
            }
        }

        function toggleCustomerTypeFields() {
            const type = document.getElementById('type').value;
            const companyFields = ['commercial_register_field', 'contact_person_field', 'contact_person_phone_field'];

            companyFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    if (type === 'company') {
                        field.classList.remove('hidden');
                        field.querySelector('input')?.setAttribute('required', 'required');
                    } else {
                        field.classList.add('hidden');
                        field.querySelector('input')?.removeAttribute('required');
                    }
                }
            });
        }

        function nextStep() {
            // Validate current step
            if (!validateStep(currentStep)) {
                return;
            }

            // Hide current step
            document.getElementById(`step${currentStep}`).classList.add('hidden');

            // Show next step
            currentStep++;
            document.getElementById(`step${currentStep}`).classList.remove('hidden');

            // Update progress indicator
            updateProgressIndicator();

            // If it's the review step, populate review data
            if (currentStep === 4) {
                populateReviewData();
            }
        }

        function prevStep() {
            // Hide current step
            document.getElementById(`step${currentStep}`).classList.add('hidden');

            // Show previous step
            currentStep--;
            document.getElementById(`step${currentStep}`).classList.remove('hidden');

            // Update progress indicator
            updateProgressIndicator();
        }

        function validateStep(step) {
            let isValid = true;

            switch(step) {
                case 1:
                    const selectedCompany = document.querySelector('input[name="company_id"]:checked');
                    if (!selectedCompany) {
                        showNotification('يرجى اختيار شركة', 'error');
                        isValid = false;
                    }
                    break;

                case 2:
                    const name = document.getElementById('name');
                    const type = document.getElementById('type');

                    if (!name.value.trim()) {
                        showError(name, 'يرجى إدخال اسم العميل');
                        isValid = false;
                    }

                    if (!type.value) {
                        showError(type, 'يرجى اختيار نوع العميل');
                        isValid = false;
                    }
                    break;
            }

            return isValid;
        }

        function showError(element, message) {
            // Remove existing error
            const existingError = element.parentElement.querySelector('.text-red-600');
            if (existingError) existingError.remove();

            // Add error class to input
            element.classList.add('border-red-500');

            // Create error message
            const errorElement = document.createElement('p');
            errorElement.className = 'mt-2 text-sm text-red-600';
            errorElement.textContent = message;
            element.parentElement.appendChild(errorElement);

            // Scroll to error
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });

            // Auto-remove error when user starts typing
            element.addEventListener('input', function() {
                this.classList.remove('border-red-500');
                if (errorElement.parentElement) {
                    errorElement.remove();
                }
            }, { once: true });
        }

        function updateProgressIndicator() {
            // Update step numbers in progress bar
            const steps = document.querySelectorAll('.flex.items-center.justify-center > div:nth-child(3n-1)');
            steps.forEach((step, index) => {
                if (index + 1 < currentStep) {
                    step.className = 'flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-full';
                } else if (index + 1 === currentStep) {
                    step.className = 'flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-full';
                } else {
                    step.className = 'flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-600 rounded-full';
                }
            });

            // Update connectors
            const connectors = document.querySelectorAll('.flex.items-center.justify-center > div:nth-child(3n)');
            connectors.forEach((connector, index) => {
                if (index + 1 < currentStep) {
                    connector.className = 'h-1 w-16 bg-indigo-600';
                } else {
                    connector.className = 'h-1 w-16 bg-gray-300';
                }
            });

            // Update step labels
            const labels = document.querySelectorAll('.flex.justify-between.mt-2 > span');
            labels.forEach((label, index) => {
                if (index + 1 < currentStep) {
                    label.className = 'text-indigo-600 font-medium';
                } else if (index + 1 === currentStep) {
                    label.className = 'text-indigo-600 font-medium';
                } else {
                    label.className = 'text-gray-500';
                }
            });
        }

        function populateReviewData() {
            // Get selected company
            const selectedCompany = document.querySelector('input[name="company_id"]:checked');
            if (selectedCompany && companies[selectedCompany.value]) {
                document.getElementById('review_company').textContent = companies[selectedCompany.value].name;
            }

            // Get form values
            document.getElementById('review_name').textContent = document.getElementById('name').value || '--';
            document.getElementById('review_type').textContent =
                document.getElementById('type').value === 'individual' ? 'فرد' :
                    document.getElementById('type').value === 'company' ? 'شركة' : '--';
            document.getElementById('review_email').textContent = document.getElementById('email').value || '--';
            document.getElementById('review_phone').textContent = document.getElementById('phone').value || '--';
            document.getElementById('review_tax_number').textContent = document.getElementById('tax_number').value || '--';
            document.getElementById('review_address').textContent = document.getElementById('address').value || '--';
            document.getElementById('review_notes').textContent = document.getElementById('notes').value || '--';

            // Payment terms translation
            const paymentTermsMap = {
                'net_30': '30 يوم صافي',
                'net_15': '15 يوم صافي',
                'due_on_receipt': 'نقداً عند الاستلام',
                'custom': 'مخصص'
            };
            const paymentTerms = document.getElementById('payment_terms').value;
            document.getElementById('review_payment_terms').textContent = paymentTermsMap[paymentTerms] || '--';
        }

        // Form submission
        document.getElementById('customerForm').addEventListener('submit', function(e) {
            // Final validation
            if (!validateStep(currentStep)) {
                e.preventDefault();
                return;
            }

            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري الحفظ...';
            submitButton.disabled = true;

            // Re-enable button after 5 seconds if submission fails
            setTimeout(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 5000);
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize company selection
            const checkedCompany = document.querySelector('input[name="company_id"]:checked');
            if (checkedCompany) {
                selectCompany(checkedCompany.value);
            }

            // Initialize customer type fields
            toggleCustomerTypeFields();

            // Update progress indicator
            updateProgressIndicator();

            // Auto-select active company if no company is selected
            if (!checkedCompany) {
                const activeCompanyId = @json($activeCompanyId);
                if (activeCompanyId) {
                    selectCompany(activeCompanyId);
                }
            }
        });

        // Helper function to show notifications
        function showNotification(message, type = 'info') {
            // Implementation depends on your notification system
            alert(message); // Replace with your notification system
        }
    </script>
@endpush
