<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>تسجيل شركة جديدة - النظام المحاسبي</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --mobile-breakpoint: 768px;
            --tablet-breakpoint: 1024px;
        }

        * {
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Cairo', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Responsive Typography */
        html {
            font-size: 16px;
        }

        @media (max-width: 640px) {
            html {
                font-size: 14px;
            }
        }

        /* Hide scrollbar */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Touch-friendly buttons */
        button,
        a.btn {
            min-height: 44px;
            min-width: 44px;
        }

        /* Registration Card */
        .register-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            -webkit-backdrop-filter: blur(10px);
        }

        @supports not (backdrop-filter: blur(10px)) {
            .register-card {
                background: rgba(255, 255, 255, 0.95);
            }
        }

        /* Input Groups */
        .input-group {
            transition: all 0.3s ease;
        }

        .input-group:focus-within {
            transform: translateY(-2px);
        }

        /* Step Indicators */
        .step-indicator {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: bold;
            position: relative;
            flex-shrink: 0;
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
            min-width: 30px;
            background: #e5e7eb;
            margin: 0 5px;
        }

        .step-line.active {
            background: #4f46e5;
        }

        .step-line.completed {
            background: #10b981;
        }

        /* Password Strength */
        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .password-weak { background: #ef4444; width: 25%; }
        .password-fair { background: #f59e0b; width: 50%; }
        .password-good { background: #3b82f6; width: 75%; }
        .password-strong { background: #10b981; width: 100%; }

        /* Mobile Optimizations */
        @media (max-width: 768px) {
            .step-indicator {
                width: 30px;
                height: 30px;
                font-size: 0.875rem;
            }

            .step-line {
                margin: 0 3px;
                min-width: 20px;
            }
        }

        /* Safe Area Insets for iOS */
        .safe-top {
            padding-top: env(safe-area-inset-top);
        }

        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }

        /* Touch Feedback */
        .touch-feedback:active {
            transform: scale(0.98);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .animate-pulse-slow {
            animation: pulse 2s infinite;
        }

        /* Responsive Grid */
        .responsive-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 280px), 1fr));
            gap: 1rem;
        }

        /* Form Inputs */
        input, select, textarea {
            font-size: 16px !important; /* Prevent zoom on iOS */
        }

        /* Mobile First Utilities */
        @media (min-width: 640px) {
            .sm\:responsive-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(min(100%, 280px), 1fr));
                gap: 1rem;
            }
        }

        /* Loading Spinner */
        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #4f46e5;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-3 sm:p-4 safe-top safe-bottom">
<div class="w-full max-w-6xl animate-fade-in">
    <!-- Mobile Back Button -->
    <button onclick="window.history.back()"
            class="md:hidden fixed top-4 right-4 z-10 bg-white shadow-lg w-10 h-10 rounded-full flex items-center justify-center touch-feedback">
        <i class="fas fa-arrow-right text-gray-600"></i>
    </button>

    <!-- البطاقة الرئيسية -->
    <div class="register-card rounded-2xl shadow-2xl overflow-hidden">
        <div class="flex flex-col lg:flex-row">
            <!-- الجانب الأيسر (التسجيل) -->
            <div class="lg:w-2/3 p-4 sm:p-6 md:p-8 lg:p-10">
                <!-- شريط التقدم (Mobile Compact) -->
                <div class="flex items-center justify-center mb-6 sm:mb-8">
                    <div class="flex items-center w-full max-w-md">
                        <!-- الخطوة 1 -->
                        <div class="flex flex-col items-center">
                            <div class="step-indicator active">
                                <span>1</span>
                            </div>
                            <span class="text-xs mt-1 hidden sm:block">الشركة</span>
                        </div>
                        <div class="step-line active"></div>

                        <!-- الخطوة 2 -->
                        <div class="flex flex-col items-center">
                            <div class="step-indicator">
                                <span>2</span>
                            </div>
                            <span class="text-xs mt-1 hidden sm:block">المسؤول</span>
                        </div>
                        <div class="step-line"></div>

                        <!-- الخطوة 3 -->
                        <div class="flex flex-col items-center">
                            <div class="step-indicator">
                                <span>3</span>
                            </div>
                            <span class="text-xs mt-1 hidden sm:block">التسجيل</span>
                        </div>
                    </div>
                </div>

                <!-- العنوان -->
                <div class="text-center mb-6 sm:mb-8">
                    <div class="flex items-center justify-center mb-3">
                        <div class="bg-indigo-100 p-3 rounded-full ml-2">
                            <i class="fas fa-building text-indigo-600 text-xl sm:text-2xl"></i>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                            تسجيل شركة جديدة
                        </h1>
                    </div>
                    <p class="text-gray-600 text-sm sm:text-base max-w-2xl mx-auto px-2">
                        سجل شركتك في النظام المحاسبي واستمتع بمميزات إدارة فواتيرك وعملائك بكل سهولة
                    </p>
                </div>

                <!-- رسائل الخطأ/النجاح -->
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 sm:mb-6 flex items-start">
                        <i class="fas fa-exclamation-circle ml-2 mt-0.5 flex-shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 sm:mb-6 flex items-start">
                        <i class="fas fa-check-circle ml-2 mt-0.5 flex-shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- نموذج التسجيل -->
                <form action="{{ route('register.store') }}" method="POST" id="registrationForm">
                    @csrf

                    <div class="space-y-6 sm:space-y-8">
                        <!-- معلومات الشركة -->
                        <div>
                            <div class="flex items-center mb-4 sm:mb-6">
                                <div class="bg-indigo-100 p-2 rounded-lg ml-2 sm:ml-3">
                                    <i class="fas fa-info-circle text-indigo-600"></i>
                                </div>
                                <h2 class="text-lg sm:text-xl font-semibold text-gray-800">
                                    معلومات الشركة الأساسية
                                </h2>
                            </div>

                            <div class="responsive-grid gap-4 sm:gap-6">
                                <!-- اسم الشركة -->
                                <div class="input-group">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-building text-gray-400 ml-1 sm:ml-2"></i>
                                        اسم الشركة *
                                    </label>
                                    <div class="relative">
                                        <input type="text"
                                               name="company_name"
                                               value="{{ old('company_name') }}"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-200 text-sm sm:text-base"
                                               placeholder="مثال: شركة التقنية المتطورة">
                                        <div class="absolute left-3 top-3.5">
                                            <i class="fas fa-city text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('company_name')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- الرقم الضريبي -->
                                <div class="input-group">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-receipt text-gray-400 ml-1 sm:ml-2"></i>
                                        الرقم الضريبي
                                    </label>
                                    <div class="relative">
                                        <input type="text"
                                               name="tax_number"
                                               value="{{ old('tax_number') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-200 text-sm sm:text-base"
                                               placeholder="مثال: 310000000000003">
                                        <div class="absolute left-3 top-3.5">
                                            <i class="fas fa-percentage text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('tax_number')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- البريد الإلكتروني -->
                                <div class="input-group">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-envelope text-gray-400 ml-1 sm:ml-2"></i>
                                        البريد الإلكتروني *
                                    </label>
                                    <div class="relative">
                                        <input type="email"
                                               name="email"
                                               value="{{ old('email') }}"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-200 text-sm sm:text-base"
                                               placeholder="company@example.com">
                                        <div class="absolute left-3 top-3.5">
                                            <i class="fas fa-at text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('email')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- الهاتف -->
                                <div class="input-group">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-phone text-gray-400 ml-1 sm:ml-2"></i>
                                        رقم الهاتف *
                                    </label>
                                    <div class="relative">
                                        <input type="tel"
                                               name="phone"
                                               value="{{ old('phone') }}"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-200 text-sm sm:text-base"
                                               placeholder="مثال: 0555555555">
                                        <div class="absolute left-3 top-3.5">
                                            <i class="fas fa-mobile-alt text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('phone')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- النطاق الفرعي -->
                                <div class="input-group col-span-full">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-globe text-gray-400 ml-1 sm:ml-2"></i>
                                        النطاق الفرعي *
                                    </label>
                                    <div class="relative">
                                        <div class="flex">
                                            <div class="relative flex-1">
                                                <input type="text"
                                                       name="domain"
                                                       value="{{ old('domain') }}"
                                                       required
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-200 text-sm sm:text-base"
                                                       placeholder="company-name">
                                                <div class="absolute left-3 top-3.5">
                                                    <i class="fas fa-link text-gray-400"></i>
                                                </div>
                                            </div>
                                            <div class="bg-gray-100 px-3 sm:px-4 py-3 border border-r-0 border-gray-300 rounded-r-lg text-gray-600 text-sm sm:text-base whitespace-nowrap overflow-x-auto hide-scrollbar max-w-[150px] sm:max-w-none">
                                                .accounting-system.com
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                                        سيتم الوصول لشركتك عبر:
                                        <span id="domain-preview" class="text-indigo-600 font-medium break-all"></span>
                                    </p>
                                    @error('domain')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- العنوان -->
                                <div class="input-group col-span-full">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-map-marker-alt text-gray-400 ml-1 sm:ml-2"></i>
                                        عنوان الشركة
                                    </label>
                                    <div class="relative">
                                        <textarea name="address"
                                                  rows="2"
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-200 text-sm sm:text-base"
                                                  placeholder="العنوان الكامل للشركة">{{ old('address') }}</textarea>
                                        <div class="absolute left-3 top-3">
                                            <i class="fas fa-location-arrow text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('address')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- معلومات المسؤول -->
                        <div>
                            <div class="flex items-center mb-4 sm:mb-6">
                                <div class="bg-blue-100 p-2 rounded-lg ml-2 sm:ml-3">
                                    <i class="fas fa-user-shield text-blue-600"></i>
                                </div>
                                <h2 class="text-lg sm:text-xl font-semibold text-gray-800">
                                    معلومات المسؤول الرئيسي
                                </h2>
                            </div>

                            <div class="responsive-grid gap-4 sm:gap-6">
                                <!-- اسم المسؤول -->
                                <div class="input-group">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-user text-gray-400 ml-1 sm:ml-2"></i>
                                        الاسم الكامل *
                                    </label>
                                    <div class="relative">
                                        <input type="text"
                                               name="name"
                                               value="{{ old('name') }}"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-200 text-sm sm:text-base"
                                               placeholder="الاسم الكامل للمسؤول">
                                        <div class="absolute left-3 top-3.5">
                                            <i class="fas fa-id-card text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('name')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- كلمة المرور -->
                                <div class="input-group">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-lock text-gray-400 ml-1 sm:ml-2"></i>
                                        كلمة المرور *
                                    </label>
                                    <div class="relative">
                                        <input type="password"
                                               name="password"
                                               id="password"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-200 text-sm sm:text-base pr-10"
                                               placeholder="كلمة مرور قوية">
                                        <div class="absolute left-3 top-3.5">
                                            <i class="fas fa-key text-gray-400"></i>
                                        </div>
                                        <button type="button"
                                                class="absolute left-10 top-3.5 text-gray-400 hover:text-gray-600 touch-feedback p-1"
                                                onclick="togglePassword('password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <!-- مؤشر قوة كلمة المرور -->
                                    <div class="mt-2">
                                        <div class="password-strength" id="password-strength"></div>
                                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                                            <span id="password-text">ضعيفة</span>
                                            <span>8 أحرف على الأقل</span>
                                        </div>
                                    </div>
                                    @error('password')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- تأكيد كلمة المرور -->
                                <div class="input-group">
                                    <label class="block text-gray-700 mb-2 font-medium">
                                        <i class="fas fa-lock text-gray-400 ml-1 sm:ml-2"></i>
                                        تأكيد كلمة المرور *
                                    </label>
                                    <div class="relative">
                                        <input type="password"
                                               name="password_confirmation"
                                               id="password_confirmation"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-200 text-sm sm:text-base pr-10"
                                               placeholder="أعد إدخال كلمة المرور">
                                        <div class="absolute left-3 top-3.5">
                                            <i class="fas fa-key text-gray-400"></i>
                                        </div>
                                        <button type="button"
                                                class="absolute left-10 top-3.5 text-gray-400 hover:text-gray-600 touch-feedback p-1"
                                                onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="mt-2 min-h-[20px]" id="password-match"></div>
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
                                       class="mt-1 ml-3 h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500 flex-shrink-0">
                                <label for="terms" class="text-gray-700 text-sm sm:text-base">
                                    <span class="font-medium">أوافق على شروط الاستخدام وسياسة الخصوصية</span>
                                    <p class="text-xs sm:text-sm text-gray-600 mt-1">
                                        بإنشاء هذا الحساب، فإنك توافق على شروط الخدمة وسياسة الخصوصية الخاصة بالنظام المحاسبي.
                                        ستستخدم الشركة بياناتها وفقاً للقوانين واللوائح المعمول بها.
                                    </p>
                                </label>
                            </div>
                            @error('terms')
                            <p class="text-red-500 text-xs sm:text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- أزرار التحكم -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 border-t">
                            <button type="submit"
                                    id="submitBtn"
                                    class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 sm:py-4 px-4 sm:px-6 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition duration-300 flex items-center justify-center touch-feedback min-h-[52px] animate-pulse-slow">
                                <i class="fas fa-rocket ml-2 text-sm sm:text-base"></i>
                                <span class="text-sm sm:text-base">أنشئ شركتي الآن</span>
                            </button>

                            <a href="{{ route('welcome') }}"
                               class="bg-gray-100 text-gray-700 py-3 sm:py-4 px-4 sm:px-6 rounded-lg font-semibold hover:bg-gray-200 transition duration-300 flex items-center justify-center touch-feedback min-h-[52px]">
                                <i class="fas fa-arrow-right ml-2 text-sm sm:text-base"></i>
                                <span class="text-sm sm:text-base">العودة للرئيسية</span>
                            </a>
                        </div>

                        <!-- روابط إضافية -->
                        <div class="text-center pt-4">
                            <p class="text-gray-600 text-sm sm:text-base">
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
            <div class="lg:w-1/3 bg-gradient-to-b from-indigo-600 to-purple-700 text-white p-6 sm:p-8 lg:p-10 order-first lg:order-last">
                <div class="h-full flex flex-col">
                    <!-- شعار الجانب الأيمن (Mobile فقط) -->
                    <div class="lg:hidden text-center mb-6">
                        <div class="bg-white/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calculator text-2xl"></i>
                        </div>
                        <h2 class="text-xl font-bold">النظام المحاسبي الذكي</h2>
                        <p class="text-indigo-200 text-sm">مخصص للشركات الصغيرة والمتوسطة</p>
                    </div>

                    <!-- المميزات -->
                    <div class="space-y-4 sm:space-y-6 mb-6 sm:mb-8">
                        <div class="flex items-start">
                            <div class="bg-white/20 p-2 rounded-lg ml-2 sm:ml-3 flex-shrink-0">
                                <i class="fas fa-check-circle text-sm sm:text-base"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1 text-sm sm:text-base">14 يوم تجربة مجانية</h4>
                                <p class="text-xs sm:text-sm text-indigo-200">جرب النظام مجاناً بدون أي رسوم</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-white/20 p-2 rounded-lg ml-2 sm:ml-3 flex-shrink-0">
                                <i class="fas fa-check-circle text-sm sm:text-base"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1 text-sm sm:text-base">دعم فني على مدار الساعة</h4>
                                <p class="text-xs sm:text-sm text-indigo-200">فريق دعم متخصص لمساعدتك</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-white/20 p-2 rounded-lg ml-2 sm:ml-3 flex-shrink-0">
                                <i class="fas fa-check-circle text-sm sm:text-base"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1 text-sm sm:text-base">بيانات آمنة ومشفرة</h4>
                                <p class="text-xs sm:text-sm text-indigo-200">حماية كاملة لبيانات شركتك</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-white/20 p-2 rounded-lg ml-2 sm:ml-3 flex-shrink-0">
                                <i class="fas fa-check-circle text-sm sm:text-base"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1 text-sm sm:text-base">استعادة بيانات مجانية</h4>
                                <p class="text-xs sm:text-sm text-indigo-200">نسخ احتياطية يومية للبيانات</p>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات -->
                    <div class="bg-white/10 rounded-xl p-4 mb-6 sm:mb-8">
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <div class="text-xl sm:text-2xl font-bold">1,200+</div>
                                <div class="text-xs sm:text-sm text-indigo-200">شركة مسجلة</div>
                            </div>
                            <div>
                                <div class="text-xl sm:text-2xl font-bold">50,000+</div>
                                <div class="text-xs sm:text-sm text-indigo-200">فاتورة شهرياً</div>
                            </div>
                        </div>
                    </div>

                    <!-- شهادة (Desktop فقط) -->
                    <div class="hidden lg:block mt-auto pt-6 border-t border-white/20">
                        <div class="flex items-center">
                            <div class="ml-3">
                                <p class="text-sm italic">"باستخدام هذا النظام، وفرنا 70% من الوقت المستغرق في العمليات المحاسبية"</p>
                                <p class="text-xs mt-2 text-indigo-200">- محمد أحمد، مدير مالي</p>
                            </div>
                        </div>
                    </div>

                    <!-- شعار الجانب الأيمن (Desktop فقط) -->
                    <div class="hidden lg:block text-center mb-8">
                        <div class="bg-white/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calculator text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold">النظام المحاسبي الذكي</h2>
                        <p class="text-indigo-200">مخصص للشركات الصغيرة والمتوسطة</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الرسالة السفلية -->
    <div class="text-center mt-4 sm:mt-6 text-gray-600 text-xs sm:text-sm">
        <p>
            <i class="fas fa-shield-alt ml-1"></i>
            نحمي بياناتك وفق أعلى معايير الأمان.
            <a href="#" class="text-indigo-600 hover:text-indigo-800">اقرأ المزيد عن أماننا</a>
        </p>
    </div>
</div>

<!-- JavaScript -->
<script>
    // إعدادات إضافية للأجهزة المحمولة
    function isMobile() {
        return window.innerWidth <= 768;
    }

    // إدارة الحجم المتغير للعناصر
    function manageResponsiveElements() {
        const width = window.innerWidth;

        // تكبير الحقول على الجوال
        if (width < 640) {
            document.querySelectorAll('input, textarea').forEach(input => {
                input.classList.add('py-3');
            });
        }
    }

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

    // تحسين تجربة الإدخال على الجوال
    domainInput.addEventListener('focus', function() {
        if (isMobile()) {
            this.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

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
                    <div class="flex items-center text-green-600 text-xs sm:text-sm">
                        <i class="fas fa-check-circle ml-1 sm:ml-2 flex-shrink-0"></i>
                        <span class="break-words">كلمتا المرور متطابقتان</span>
                    </div>
                `;
        } else {
            passwordMatch.innerHTML = `
                    <div class="flex items-center text-red-600 text-xs sm:text-sm">
                        <i class="fas fa-times-circle ml-1 sm:ml-2 flex-shrink-0"></i>
                        <span class="break-words">كلمتا المرور غير متطابقتين</span>
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
        e.preventDefault();

        const password = passwordInput.value;
        const confirm = confirmPassword.value;
        const terms = document.getElementById('terms');

        // التحقق من تطابق كلمات المرور
        if (password !== confirm) {
            showNotification('كلمتا المرور غير متطابقتين. الرجاء التأكد من مطابقة كلمة المرور.', 'error');
            confirmPassword.focus();
            return;
        }

        // التحقق من شروط الاستخدام
        if (!terms.checked) {
            showNotification('يجب الموافقة على شروط الاستخدام وسياسة الخصوصية.', 'error');
            terms.focus();
            return;
        }

        // التحقق من قوة كلمة المرور
        if (password.length < 8) {
            showNotification('كلمة المرور يجب أن تكون 8 أحرف على الأقل.', 'error');
            passwordInput.focus();
            return;
        }

        // عرض رسالة التحميل
        const submitBtn = document.getElementById('submitBtn');
        const originalHtml = submitBtn.innerHTML;
        submitBtn.innerHTML = `
                <div class="loader"></div>
                <span class="mr-2 text-sm sm:text-base">جاري إنشاء حساب شركتك...</span>
            `;
        submitBtn.disabled = true;
        submitBtn.classList.remove('animate-pulse-slow');

        // إرسال النموذج بعد تأخير قصير لعرض التحميل
        setTimeout(() => {
            this.submit();
        }, 500);
    });

    // التحقق من صحة النطاق
    domainInput.addEventListener('blur', function() {
        const domain = this.value.trim().toLowerCase();
        const cleanDomain = domain.replace(/[^a-z0-9-]/g, '');
        this.value = cleanDomain;

        if (cleanDomain.length < 3) {
            showNotification('النطاق الفرعي يجب أن يكون 3 أحرف على الأقل', 'warning');
            this.focus();
        }
    });

    // إظهار الإشعارات
    function showNotification(message, type = 'info') {
        const colors = {
            success: 'bg-green-100 border-green-200 text-green-800',
            error: 'bg-red-100 border-red-200 text-red-800',
            info: 'bg-blue-100 border-blue-200 text-blue-800',
            warning: 'bg-yellow-100 border-yellow-200 text-yellow-800'
        };

        const notification = document.createElement('div');
        notification.className = `fixed top-4 sm:top-6 right-4 left-4 sm:left-auto sm:max-w-md ${colors[type]} px-4 py-3 rounded-lg shadow-lg z-50 flex items-center justify-between animate-fade-in`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} ml-2 flex-shrink-0"></i>
                <span class="text-sm">${message}</span>
            </div>
            <button type="button" class="text-gray-500 hover:text-gray-700 touch-feedback p-1" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;

        document.body.appendChild(notification);

        // إزالة تلقائية بعد 5 ثواني
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // التحكم في لوحة المفاتيح على الجوال
    document.addEventListener('DOMContentLoaded', function() {
        manageResponsiveElements();

        // إضافة touch-feedback class لجميع الأزرار
        document.querySelectorAll('button, a.btn, .touch-feedback').forEach(element => {
            element.classList.add('touch-feedback');
        });

        // التعامل مع تغيير اتجاه الشاشة
        window.addEventListener('orientationchange', function() {
            setTimeout(() => {
                manageResponsiveElements();
            }, 100);
        });

        // التعامل مع تغيير حجم النافذة
        window.addEventListener('resize', function() {
            manageResponsiveElements();
        });

        // تركيز أول حقل عند تحميل الصفحة
        setTimeout(() => {
            const firstInput = document.querySelector('input');
            if (firstInput && isMobile()) {
                firstInput.focus();
            }
        }, 500);
    });

    // منع التمرير عند التركيز على الحقول على iOS
    let scrollPosition = 0;
    document.addEventListener('focusin', function(e) {
        if (isMobile() && (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA')) {
            scrollPosition = window.pageYOffset;
            document.body.style.overflow = 'hidden';
        }
    });

    document.addEventListener('focusout', function() {
        if (isMobile()) {
            document.body.style.overflow = '';
            window.scrollTo(0, scrollPosition);
        }
    });
</script>
</body>
</html>
