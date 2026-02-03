{{--<x-guest-layout>--}}
{{--    <form method="POST" action="{{ route('register') }}">--}}
{{--        @csrf--}}

{{--        <!-- Name -->--}}
{{--        <div>--}}
{{--            <x-input-label for="name" :value="__('Name')" />--}}
{{--            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />--}}
{{--            <x-input-error :messages="$errors->get('name')" class="mt-2" />--}}
{{--        </div>--}}

{{--        <!-- Email Address -->--}}
{{--        <div class="mt-4">--}}
{{--            <x-input-label for="email" :value="__('Email')" />--}}
{{--            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />--}}
{{--            <x-input-error :messages="$errors->get('email')" class="mt-2" />--}}
{{--        </div>--}}

{{--        <!-- Password -->--}}
{{--        <div class="mt-4">--}}
{{--            <x-input-label for="password" :value="__('Password')" />--}}

{{--            <x-text-input id="password" class="block mt-1 w-full"--}}
{{--                            type="password"--}}
{{--                            name="password"--}}
{{--                            required autocomplete="new-password" />--}}

{{--            <x-input-error :messages="$errors->get('password')" class="mt-2" />--}}
{{--        </div>--}}

{{--        <!-- Confirm Password -->--}}
{{--        <div class="mt-4">--}}
{{--            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />--}}

{{--            <x-text-input id="password_confirmation" class="block mt-1 w-full"--}}
{{--                            type="password"--}}
{{--                            name="password_confirmation" required autocomplete="new-password" />--}}

{{--            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />--}}
{{--        </div>--}}

{{--        <div class="flex items-center justify-end mt-4">--}}
{{--            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">--}}
{{--                {{ __('Already registered?') }}--}}
{{--            </a>--}}

{{--            <x-primary-button class="ms-4">--}}
{{--                {{ __('Register') }}--}}
{{--            </x-primary-button>--}}
{{--        </div>--}}
{{--    </form>--}}
{{--</x-guest-layout>--}}
    <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل شركة جديدة - النظام المحاسبي</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .input-group {
            transition: all 0.3s ease;
        }

        .input-group:focus-within {
            transform: translateY(-2px);
        }

        .step-indicator {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: bold;
            position: relative;
        }

        .step-indicator.active {
            background: #4f46e5;
            color: white;
        }

        .step-indicator.completed {
            background: #10b981;
            color: white;
        }

        .step-line {
            height: 2px;
            flex: 1;
            background: #e5e7eb;
            margin: 0 10px;
        }

        .step-line.active {
            background: #4f46e5;
        }

        .step-line.completed {
            background: #10b981;
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .password-weak { background: #ef4444; width: 25%; }
        .password-fair { background: #f59e0b; width: 50%; }
        .password-good { background: #3b82f6; width: 75%; }
        .password-strong { background: #10b981; width: 100%; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
<div class="w-full max-w-6xl">
    <!-- البطاقة الرئيسية -->
    <div class="register-card rounded-2xl shadow-2xl overflow-hidden">
        <div class="md:flex">
            <!-- الجانب الأيسر (التسجيل) -->
            <div class="md:w-2/3 p-8 md:p-12">
                <!-- شريط التقدم -->
                <div class="flex items-center justify-center mb-10">
                    <div class="flex items-center">
                        <!-- الخطوة 1 -->
                        <div class="step-indicator active">
                            <span>1</span>
                        </div>
                        <div class="step-line active"></div>

                        <!-- الخطوة 2 -->
                        <div class="step-indicator">
                            <span>2</span>
                        </div>
                        <div class="step-line"></div>

                        <!-- الخطوة 3 -->
                        <div class="step-indicator">
                            <span>3</span>
                        </div>
                    </div>
                </div>

                <!-- العنوان -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-building text-indigo-600 ml-2"></i>
                        تسجيل شركة جديدة
                    </h1>
                    <p class="text-gray-600">
                        سجل شركتك في النظام المحاسبي واستمتع بمميزات إدارة فواتيرك وعملائك بكل سهولة
                    </p>
                </div>

                <!-- رسائل الخطأ/النجاح -->
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle ml-2"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle ml-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <!-- نموذج التسجيل -->
                <form action="{{ route('register.store') }}" method="POST" id="registrationForm">
                    @csrf

                    <div class="space-y-6">
                        <!-- معلومات الشركة -->
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-r-4 border-indigo-500 pr-3">
                                <i class="fas fa-info-circle text-indigo-600 ml-2"></i>
                                معلومات الشركة الأساسية
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- اسم الشركة -->
                                <div class="input-group">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-building text-gray-400 ml-2"></i>
                                        اسم الشركة *
                                    </label>
                                    <div class="relative">
                                        <input type="text"
                                               name="company_name"
                                               value="{{ old('company_name') }}"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-300"
                                               placeholder="مثال: شركة التقنية المتطورة">
                                        <div class="absolute left-3 top-3">
                                            <i class="fas fa-city text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('company_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- الرقم الضريبي -->
                                <div class="input-group">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-receipt text-gray-400 ml-2"></i>
                                        الرقم الضريبي
                                    </label>
                                    <div class="relative">
                                        <input type="text"
                                               name="tax_number"
                                               value="{{ old('tax_number') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-300"
                                               placeholder="مثال: 310000000000003">
                                        <div class="absolute left-3 top-3">
                                            <i class="fas fa-percentage text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('tax_number')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- البريد الإلكتروني -->
                                <div class="input-group">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-envelope text-gray-400 ml-2"></i>
                                        البريد الإلكتروني *
                                    </label>
                                    <div class="relative">
                                        <input type="email"
                                               name="email"
                                               value="{{ old('email') }}"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-300"
                                               placeholder="company@example.com">
                                        <div class="absolute left-3 top-3">
                                            <i class="fas fa-at text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- الهاتف -->
                                <div class="input-group">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-phone text-gray-400 ml-2"></i>
                                        رقم الهاتف *
                                    </label>
                                    <div class="relative">
                                        <input type="tel"
                                               name="phone"
                                               value="{{ old('phone') }}"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-300"
                                               placeholder="مثال: 0555555555">
                                        <div class="absolute left-3 top-3">
                                            <i class="fas fa-mobile-alt text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- العنوان -->
                                <div class="input-group md:col-span-2">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-map-marker-alt text-gray-400 ml-2"></i>
                                        عنوان الشركة
                                    </label>
                                    <div class="relative">
                                            <textarea name="address"
                                                      rows="2"
                                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-300"
                                                      placeholder="العنوان الكامل للشركة">{{ old('address') }}</textarea>
                                        <div class="absolute left-3 top-3">
                                            <i class="fas fa-location-arrow text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- النطاق الفرعي -->
                                <div class="input-group">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-globe text-gray-400 ml-2"></i>
                                        النطاق الفرعي *
                                    </label>
                                    <div class="relative">
                                        <div class="flex">
                                            <input type="text"
                                                   name="domain"
                                                   value="{{ old('domain') }}"
                                                   required
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-300"
                                                   placeholder="company-name">
                                            <div class="bg-gray-100 px-4 py-3 border border-r-0 border-gray-300 rounded-r-lg text-gray-600">
                                                .accounting-system.com
                                            </div>
                                        </div>
                                        <div class="absolute left-3 top-3">
                                            <i class="fas fa-link text-gray-400"></i>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        سيتم الوصول لشركتك عبر: <span id="domain-preview" class="text-indigo-600 font-medium"></span>
                                    </p>
                                    @error('domain')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- معلومات المسؤول -->
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-r-4 border-blue-500 pr-3">
                                <i class="fas fa-user-shield text-blue-600 ml-2"></i>
                                معلومات المسؤول الرئيسي
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- اسم المسؤول -->
                                <div class="input-group">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-user text-gray-400 ml-2"></i>
                                        الاسم الكامل *
                                    </label>
                                    <div class="relative">
                                        <input type="text"
                                               name="name"
                                               value="{{ old('name') }}"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-300"
                                               placeholder="الاسم الكامل للمسؤول">
                                        <div class="absolute left-3 top-3">
                                            <i class="fas fa-id-card text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- كلمة المرور -->
                                <div class="input-group">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-lock text-gray-400 ml-2"></i>
                                        كلمة المرور *
                                    </label>
                                    <div class="relative">
                                        <input type="password"
                                               name="password"
                                               id="password"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-300"
                                               placeholder="كلمة مرور قوية">
                                        <div class="absolute left-3 top-3">
                                            <i class="fas fa-key text-gray-400"></i>
                                        </div>
                                        <button type="button"
                                                class="absolute left-10 top-3 text-gray-400 hover:text-gray-600"
                                                onclick="togglePassword('password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <!-- مؤشر قوة كلمة المرور -->
                                    <div class="mt-2">
                                        <div class="password-strength" id="password-strength"></div>
                                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                                            <span id="password-text">ضعيفة</span>
                                            <span>يجب أن تكون 8 أحرف على الأقل</span>
                                        </div>
                                    </div>
                                    @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- تأكيد كلمة المرور -->
                                <div class="input-group">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-lock text-gray-400 ml-2"></i>
                                        تأكيد كلمة المرور *
                                    </label>
                                    <div class="relative">
                                        <input type="password"
                                               name="password_confirmation"
                                               id="password_confirmation"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-300"
                                               placeholder="أعد إدخال كلمة المرور">
                                        <div class="absolute left-3 top-3">
                                            <i class="fas fa-key text-gray-400"></i>
                                        </div>
                                        <button type="button"
                                                class="absolute left-10 top-3 text-gray-400 hover:text-gray-600"
                                                onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="mt-2" id="password-match"></div>
                                </div>
                            </div>
                        </div>

                        <!-- شروط الاستخدام -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <input type="checkbox"
                                       name="terms"
                                       id="terms"
                                       required
                                       class="mt-1 ml-3 h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500">
                                <label for="terms" class="text-gray-700">
                                    <span class="font-medium">أوافق على شروط الاستخدام وسياسة الخصوصية</span>
                                    <p class="text-sm text-gray-600 mt-1">
                                        بإنشاء هذا الحساب، فإنك توافق على شروط الخدمة وسياسة الخصوصية الخاصة بالنظام المحاسبي.
                                        ستستخدم الشركة بياناتها وفقاً للقوانين واللوائح المعمول بها.
                                    </p>
                                </label>
                            </div>
                            @error('terms')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- أزرار التحكم -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t">
                            <button type="submit"
                                    id="submitBtn"
                                    class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 px-6 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition duration-300 flex items-center justify-center">
                                <i class="fas fa-rocket ml-2"></i>
                                <span>أنشئ شركتي الآن</span>
                            </button>

                            <a href="{{ route('welcome') }}"
                               class="bg-gray-100 text-gray-700 py-4 px-6 rounded-lg font-semibold hover:bg-gray-200 transition duration-300 flex items-center justify-center">
                                <i class="fas fa-arrow-right ml-2"></i>
                                <span>العودة للرئيسية</span>
                            </a>
                        </div>

                        <!-- روابط إضافية -->
                        <div class="text-center pt-4">
                            <p class="text-gray-600">
                                لديك حساب بالفعل؟
                                <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                    <i class="fas fa-sign-in-alt ml-1"></i> سجل الدخول
                                </a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>

            <!-- الجانب الأيمن (المعلومات) -->
            <div class="md:w-1/3 bg-gradient-to-b from-indigo-600 to-purple-700 text-white p-8 md:p-12">
                <div class="h-full flex flex-col justify-center">
                    <!-- الشعار -->
                    <div class="text-center mb-8">
                        <div class="bg-white/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calculator text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold">النظام المحاسبي الذكي</h2>
                        <p class="text-indigo-200">مخصص للشركات الصغيرة والمتوسطة</p>
                    </div>

                    <!-- المميزات -->
                    <div class="space-y-6 mb-8">
                        <div class="flex items-start">
                            <div class="bg-white/20 p-2 rounded-lg ml-3">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">14 يوم تجربة مجانية</h4>
                                <p class="text-sm text-indigo-200">جرب النظام مجاناً بدون أي رسوم</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-white/20 p-2 rounded-lg ml-3">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">دعم فني على مدار الساعة</h4>
                                <p class="text-sm text-indigo-200">فريق دعم متخصص لمساعدتك</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-white/20 p-2 rounded-lg ml-3">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">بيانات آمنة ومشفرة</h4>
                                <p class="text-sm text-indigo-200">حماية كاملة لبيانات شركتك</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-white/20 p-2 rounded-lg ml-3">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">استعادة بيانات مجانية</h4>
                                <p class="text-sm text-indigo-200">نسخ احتياطية يومية للبيانات</p>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات -->
                    <div class="bg-white/10 rounded-xl p-4">
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold">1,200+</div>
                                <div class="text-sm text-indigo-200">شركة مسجلة</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold">50,000+</div>
                                <div class="text-sm text-indigo-200">فاتورة شهرياً</div>
                            </div>
                        </div>
                    </div>

                    <!-- شهادة -->
                    <div class="mt-8 pt-6 border-t border-white/20">
                        <div class="flex items-center">
                            <div class="ml-3">
                                <p class="text-sm italic">"باستخدام هذا النظام، وفرنا 70% من الوقت المستغرق في العمليات المحاسبية"</p>
                                <p class="text-xs mt-2 text-indigo-200">- محمد أحمد، مدير مالي</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الرسالة السفلية -->
    <div class="text-center mt-6 text-gray-600 text-sm">
        <p>
            <i class="fas fa-shield-alt ml-1"></i>
            نحمي بياناتك وفق أعلى معايير الأمان.
            <a href="#" class="text-indigo-600 hover:text-indigo-800">اقرأ المزيد عن أماننا</a>
        </p>
    </div>
</div>

<!-- JavaScript -->
<script>
    // عرض النطاق الفرعي
    const domainInput = document.querySelector('input[name="domain"]');
    const domainPreview = document.getElementById('domain-preview');

    function updateDomainPreview() {
        const domain = domainInput.value.trim().toLowerCase();
        const cleanDomain = domain.replace(/[^a-z0-9-]/g, '');
        domainPreview.textContent = cleanDomain ?
            `${cleanDomain}.accounting-system.com` :
            'your-company.accounting-system.com';
    }

    domainInput.addEventListener('input', updateDomainPreview);
    updateDomainPreview();

    // التحقق من قوة كلمة المرور
    const passwordInput = document.getElementById('password');
    const passwordStrength = document.getElementById('password-strength');
    const passwordText = document.getElementById('password-text');
    const confirmPassword = document.getElementById('password_confirmation');
    const passwordMatch = document.getElementById('password-match');

    function checkPasswordStrength(password) {
        let strength = 0;

        // الطول
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;

        // التنوع
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        // التصنيف
        if (strength <= 2) {
            passwordStrength.className = 'password-strength password-weak';
            passwordText.textContent = 'ضعيفة';
            passwordText.className = 'text-red-500';
        } else if (strength <= 4) {
            passwordStrength.className = 'password-strength password-fair';
            passwordText.textContent = 'متوسطة';
            passwordText.className = 'text-yellow-500';
        } else if (strength <= 5) {
            passwordStrength.className = 'password-strength password-good';
            passwordText.textContent = 'جيدة';
            passwordText.className = 'text-blue-500';
        } else {
            passwordStrength.className = 'password-strength password-strong';
            passwordText.textContent = 'قوية جداً';
            passwordText.className = 'text-green-500';
        }
    }

    function checkPasswordMatch() {
        if (!passwordInput.value || !confirmPassword.value) {
            passwordMatch.innerHTML = '';
            return;
        }

        if (passwordInput.value === confirmPassword.value) {
            passwordMatch.innerHTML = `
                    <div class="flex items-center text-green-600 text-sm">
                        <i class="fas fa-check-circle ml-2"></i>
                        <span>كلمتا المرور متطابقتان</span>
                    </div>
                `;
        } else {
            passwordMatch.innerHTML = `
                    <div class="flex items-center text-red-600 text-sm">
                        <i class="fas fa-times-circle ml-2"></i>
                        <span>كلمتا المرور غير متطابقتين</span>
                    </div>
                `;
        }
    }

    passwordInput.addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordMatch();
    });

    confirmPassword.addEventListener('input', checkPasswordMatch);

    // إظهار/إخفاء كلمة المرور
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }

    // التحقق من النموذج قبل الإرسال
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirm = confirmPassword.value;
        const terms = document.getElementById('terms').checked;

        // التحقق من تطابق كلمات المرور
        if (password !== confirm) {
            e.preventDefault();
            alert('كلمتا المرور غير متطابقتين. الرجاء التأكد من مطابقة كلمة المرور.');
            confirmPassword.focus();
            return;
        }

        // التحقق من شروط الاستخدام
        if (!terms) {
            e.preventDefault();
            alert('يجب الموافقة على شروط الاستخدام وسياسة الخصوصية.');
            return;
        }

        // عرض رسالة التحميل
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = `
                <i class="fas fa-spinner fa-spin ml-2"></i>
                <span>جاري إنشاء حساب شركتك...</span>
            `;
        submitBtn.disabled = true;
    });

    // التحقق من صحة النطاق
    domainInput.addEventListener('blur', function() {
        const domain = this.value.trim().toLowerCase();
        const cleanDomain = domain.replace(/[^a-z0-9-]/g, '');
        this.value = cleanDomain;

        if (cleanDomain.length < 3) {
            alert('النطاق الفرعي يجب أن يكون 3 أحرف على الأقل');
            this.focus();
        }
    });
</script>
</body>
</html>
