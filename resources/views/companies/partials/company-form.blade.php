<div class="space-y-8">
    <!-- Basic Information -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-r-4 border-indigo-500 pr-3">
            <i class="fas fa-info-circle text-indigo-600 ml-2"></i>
            المعلومات الأساسية
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Company Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    اسم الشركة *
                </label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $company->name ?? '') }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500"
                       placeholder="اسم الشركة">
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    البريد الإلكتروني *
                </label>
                <input type="email"
                       name="email"
                       value="{{ old('email', $company->email ?? '') }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500"
                       placeholder="company@example.com">
                @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    رقم الهاتف
                </label>
                <input type="tel"
                       name="phone"
                       value="{{ old('phone', $company->phone ?? '') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500"
                       placeholder="05xxxxxxxx">
                @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tax Number -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    الرقم الضريبي
                </label>
                <input type="text"
                       name="tax_number"
                       value="{{ old('tax_number', $company->tax_number ?? '') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500"
                       placeholder="3xxxxxxxxxxxxxx">
                @error('tax_number')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Address -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-r-4 border-blue-500 pr-3">
            <i class="fas fa-map-marker-alt text-blue-600 ml-2"></i>
            العنوان
        </h3>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                عنوان الشركة
            </label>
            <textarea name="address"
                      rows="3"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500"
                      placeholder="العنوان الكامل للشركة">{{ old('address', $company->address ?? '') }}</textarea>
            @error('address')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Logo -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-r-4 border-purple-500 pr-3">
            <i class="fas fa-image text-purple-600 ml-2"></i>
            شعار الشركة
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Logo Preview -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    المعاينة الحالية
                </label>
                <div id="logoPreview" class="h-32 w-32 rounded-lg border bg-gray-50 flex items-center justify-center overflow-hidden">
                    @if(isset($company) && $company->logo)
                        <img src="{{ $company->logo_url }}" alt="شعار الشركة" class="h-32 w-32 object-cover">
                    @else
                        <i class="fas fa-building text-gray-400 text-3xl"></i>
                    @endif
                </div>
            </div>

            <!-- Upload Logo -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    تحميل شعار جديد
                </label>
                <input type="file"
                       name="logo"
                       id="logo"
                       accept="image/*"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                <p class="text-xs text-gray-500 mt-2">
                    أنواع الملفات المسموحة: JPG, PNG, GIF. الحد الأقصى: 2MB
                </p>
                @error('logo')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                @if(isset($company) && $company->logo)
                    <div class="mt-4">
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="remove_logo"
                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                            <span class="mr-2 text-sm text-gray-700">إزالة الشعار الحالي</span>
                        </label>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Website & Settings -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-r-4 border-yellow-500 pr-3">
            <i class="fas fa-cog text-yellow-600 ml-2"></i>
            إعدادات إضافية
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Tax Rate -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    نسبة الضريبة %
                </label>
                <input type="number"
                       name="tax_rate"
                       value="{{ old('tax_rate', $company->tax_rate ?? 15) }}"
                       min="0"
                       max="100"
                       step="0.01"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500"
                       placeholder="15">
                @error('tax_rate')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Status -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-r-4 border-gray-500 pr-3">
            <i class="fas fa-toggle-on text-gray-600 ml-2"></i>
            حالة الشركة
        </h3>

        <div>
            <label class="flex items-center">
                <input type="checkbox"
                       name="is_active"
                       {{ old('is_active', isset($company) ? $company->is_active : true) ? 'checked' : '' }}
                       class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <span class="mr-3 text-gray-700">تفعيل الشركة وجعلها نشطة</span>
            </label>
            <p class="text-sm text-gray-500 mt-2">
                عند تفعيل الشركة، يمكنك إدارة عملائها وفواتيرها. يمكنك تعطيلها مؤقتاً دون حذف البيانات.
            </p>
        </div>
    </div>
</div>
