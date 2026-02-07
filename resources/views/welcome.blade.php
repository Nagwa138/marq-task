<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>النظام المحاسبي المتعدد المستأجرين</title>

    <!-- Tailwind CSS CDN -->
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

        /* Touch-friendly buttons */
        button,
        a.btn {
            min-height: 44px;
            min-width: 44px;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        @media (hover: none) {
            .card-hover:hover {
                transform: none;
                box-shadow: none;
            }
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .feature-icon {
                width: 50px;
                height: 50px;
                margin-bottom: 0.75rem;
            }
        }

        /* Mobile Menu */
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }

        .mobile-menu.open {
            transform: translateX(0);
        }

        .backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .animate-pulse-slow {
            animation: pulse 3s infinite;
        }

        /* Safe Area Insets for iOS */
        .safe-top {
            padding-top: env(safe-area-inset-top);
        }

        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom);
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

        /* Touch Feedback */
        .touch-feedback:active {
            transform: scale(0.98);
        }

        /* Responsive Grid */
        .responsive-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 300px), 1fr));
            gap: 1.5rem;
        }

        /* Hide scrollbar */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
<!-- Mobile Menu Button -->
<button id="mobileMenuButton" class="md:hidden fixed top-4 right-4 z-50 bg-white shadow-lg w-12 h-12 rounded-full flex items-center justify-center touch-feedback">
    <i class="fas fa-bars text-xl text-gray-600"></i>
</button>

<!-- Mobile Menu Overlay -->
<div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden backdrop"></div>

<!-- Mobile Menu -->
<div id="mobileMenu" class="mobile-menu fixed top-0 right-0 h-full w-80 bg-white shadow-xl z-50 overflow-y-auto safe-top safe-bottom">
    <div class="p-4 border-b">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-calculator text-2xl text-indigo-600 ml-2"></i>
                <span class="text-xl font-bold text-gray-800">المحاسب الذكي</span>
            </div>
            <button id="closeMobileMenu" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
    </div>
    <div class="p-4">
        <ul class="space-y-2">
            <li>
                <a href="#features" onclick="closeMobileMenu()" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-star ml-3"></i>
                    <span>المميزات</span>
                </a>
            </li>
            <li>
                <a href="#how-it-works" onclick="closeMobileMenu()" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-play ml-3"></i>
                    <span>كيف يعمل</span>
                </a>
            </li>
            <li>
                <a href="#cta" onclick="closeMobileMenu()" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-rocket ml-3"></i>
                    <span>ابدأ الآن</span>
                </a>
            </li>
        </ul>
        <div class="mt-6 pt-6 border-t">
            <a href="{{ route('login') }}" class="block w-full text-center bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 mb-3">
                <i class="fas fa-sign-in-alt ml-2"></i> تسجيل الدخول
            </a>
            <a href="{{ route('register') }}" class="block w-full text-center bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700">
                <i class="fas fa-building ml-2"></i> سجل شركتك مجاناً
            </a>
        </div>
    </div>
</div>

<!-- الهيدر -->
<nav class="bg-white shadow-sm safe-top">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
        <div class="flex justify-between items-center h-14 md:h-16">
            <!-- اللوجو -->
            <div class="flex items-center">
                <div class="flex items-center">
                    <i class="fas fa-calculator text-xl md:text-2xl text-indigo-600 ml-1 md:ml-2"></i>
                    <span class="text-lg md:text-xl font-bold text-gray-800">المحاسب الذكي</span>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-2 lg:space-x-4 space-x-reverse">
                <a href="#features" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm lg:text-base">
                    المميزات
                </a>
                <a href="#how-it-works" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm lg:text-base">
                    كيف يعمل
                </a>
                <a href="#cta" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm lg:text-base">
                    ابدأ الآن
                </a>
            </div>

            <!-- Desktop Buttons -->
            <div class="hidden md:flex items-center space-x-3 space-x-reverse">
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 text-sm lg:text-base">
                    <i class="fas fa-sign-in-alt ml-1"></i> تسجيل الدخول
                </a>
                <a href="{{ route('register') }}"
                   class="bg-indigo-600 text-white px-4 lg:px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 text-sm lg:text-base min-h-[44px] flex items-center">
                    <i class="fas fa-building ml-1"></i> سجل شركتك مجاناً
                </a>
            </div>

            <!-- Mobile Logo -->
            <div class="md:hidden flex items-center">
                <i class="fas fa-calculator text-xl text-indigo-600 ml-2"></i>
                <span class="text-lg font-bold text-gray-800">المحاسب</span>
            </div>
        </div>
    </div>
</nav>

<!-- قسم البطل -->
<section class="hero-gradient text-white py-12 md:py-16 lg:py-20 safe-top">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
        <div class="text-center animate-fade-in-up">
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-4 md:mb-6 leading-tight">
                نظام محاسبي متكامل <br class="hidden sm:block"> لكل شركتك
            </h1>
            <p class="text-base sm:text-lg md:text-xl lg:text-2xl mb-6 md:mb-8 opacity-90 max-w-2xl mx-auto px-2">
                أدر فواتيرك، عملاءك، ومدفوعاتك بكل سهولة مع نظام محاسبي متعدد المستأجرين
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
                <a href="{{ route('register') }}"
                   class="bg-white text-indigo-600 px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold hover:bg-gray-100 transition duration-300 text-sm sm:text-base min-h-[52px] flex items-center justify-center touch-feedback animate-pulse-slow">
                    <i class="fas fa-rocket ml-2"></i> ابدأ مجاناً
                </a>
                <a href="#features"
                   class="bg-transparent border-2 border-white text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition duration-300 text-sm sm:text-base min-h-[52px] flex items-center justify-center touch-feedback">
                    <i class="fas fa-play-circle ml-2"></i> شاهد العرض التوضيحي
                </a>
            </div>
        </div>
    </div>
</section>

<!-- قسم المميزات -->
<section id="features" class="py-12 md:py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
        <div class="text-center mb-8 md:mb-12">
            <h2 class="text-2xl md:text-3xl font-bold mb-3 md:mb-4 text-gray-800">مميزات النظام</h2>
            <p class="text-gray-600 text-sm md:text-base max-w-2xl mx-auto px-2">
                نظام محاسبي متكامل مصمم خصيصاً لتلبية احتياجات الشركات الصغيرة والمتوسطة
            </p>
        </div>

        <div class="responsive-grid gap-6 md:gap-8">
            <!-- ميزة 1 -->
            <div class="bg-gray-50 rounded-xl p-6 md:p-8 card-hover">
                <div class="feature-icon bg-indigo-100 text-indigo-600 mx-auto md:mx-0">
                    <i class="fas fa-layer-group text-xl md:text-2xl"></i>
                </div>
                <h3 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-gray-800 text-center md:text-right">نظام متعدد المستأجرين</h3>
                <p class="text-gray-600 text-sm md:text-base text-center md:text-right">
                    كل شركة تحصل على بيئة معزولة تماماً ببياناتها الخاصة وإعداداتها المميزة
                </p>
            </div>

            <!-- ميزة 2 -->
            <div class="bg-gray-50 rounded-xl p-6 md:p-8 card-hover">
                <div class="feature-icon bg-green-100 text-green-600 mx-auto md:mx-0">
                    <i class="fas fa-file-invoice-dollar text-xl md:text-2xl"></i>
                </div>
                <h3 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-gray-800 text-center md:text-right">إدارة الفواتير</h3>
                <p class="text-gray-600 text-sm md:text-base text-center md:text-right">
                    أنشئ فواتير ببنود متعددة مع حساب تلقائي للضريبة والتخفيضات
                </p>
            </div>

            <!-- ميزة 3 -->
            <div class="bg-gray-50 rounded-xl p-6 md:p-8 card-hover">
                <div class="feature-icon bg-blue-100 text-blue-600 mx-auto md:mx-0">
                    <i class="fas fa-hand-holding-usd text-xl md:text-2xl"></i>
                </div>
                <h3 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-gray-800 text-center md:text-right">تسجيل المدفوعات</h3>
                <p class="text-gray-600 text-sm md:text-base text-center md:text-right">
                    سجل المدفوعات بطرق متعددة وتتبع حالة الفواتير تلقائياً
                </p>
            </div>

            <!-- ميزة 4 -->
            <div class="bg-gray-50 rounded-xl p-6 md:p-8 card-hover">
                <div class="feature-icon bg-purple-100 text-purple-600 mx-auto md:mx-0">
                    <i class="fas fa-users text-xl md:text-2xl"></i>
                </div>
                <h3 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-gray-800 text-center md:text-right">إدارة العملاء</h3>
                <p class="text-gray-600 text-sm md:text-base text-center md:text-right">
                    أدر قاعدة عملائك، أرصدتهم، وتاريخ معاملاتهم بكل سهولة
                </p>
            </div>

            <!-- ميزة 5 -->
            <div class="bg-gray-50 rounded-xl p-6 md:p-8 card-hover">
                <div class="feature-icon bg-yellow-100 text-yellow-600 mx-auto md:mx-0">
                    <i class="fas fa-chart-bar text-xl md:text-2xl"></i>
                </div>
                <h3 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-gray-800 text-center md:text-right">تقارير وتحليلات</h3>
                <p class="text-gray-600 text-sm md:text-base text-center md:text-right">
                    احصل على تقارير مالية مفصلة ولوحات تحكم تفاعلية لاتخاذ القرارات
                </p>
            </div>

            <!-- ميزة 6 -->
            <div class="bg-gray-50 rounded-xl p-6 md:p-8 card-hover">
                <div class="feature-icon bg-red-100 text-red-600 mx-auto md:mx-0">
                    <i class="fas fa-shield-alt text-xl md:text-2xl"></i>
                </div>
                <h3 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-gray-800 text-center md:text-right">أمان عالي</h3>
                <p class="text-gray-600 text-sm md:text-base text-center md:text-right">
                    بياناتك محمية بطبقات أمان متعددة وعزل كامل بين الشركات
                </p>
            </div>
        </div>
    </div>
</section>

<!-- قسم كيف يعمل -->
<section id="how-it-works" class="py-12 md:py-16 lg:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-8 md:mb-12 text-gray-800">كيف يعمل النظام؟</h2>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 md:gap-8">
            <!-- خطوة 1 -->
            <div class="text-center">
                <div class="bg-indigo-100 text-indigo-600 w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                    <span class="text-xl sm:text-2xl font-bold">1</span>
                </div>
                <h3 class="text-sm sm:text-base md:text-lg font-semibold mb-1 md:mb-2">سجل شركتك</h3>
                <p class="text-xs sm:text-sm text-gray-600 px-1">أنشئ حساب شركتك في دقائق</p>
            </div>

            <!-- خطوة 2 -->
            <div class="text-center">
                <div class="bg-indigo-100 text-indigo-600 w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                    <span class="text-xl sm:text-2xl font-bold">2</span>
                </div>
                <h3 class="text-sm sm:text-base md:text-lg font-semibold mb-1 md:mb-2">أضف عملائك</h3>
                <p class="text-xs sm:text-sm text-gray-600 px-1">قم بإضافة قاعدة عملائك</p>
            </div>

            <!-- خطوة 3 -->
            <div class="text-center">
                <div class="bg-indigo-100 text-indigo-600 w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                    <span class="text-xl sm:text-2xl font-bold">3</span>
                </div>
                <h3 class="text-sm sm:text-base md:text-lg font-semibold mb-1 md:mb-2">أنشئ الفواتير</h3>
                <p class="text-xs sm:text-sm text-gray-600 px-1">اصدر فواتير لعملائك بسهولة</p>
            </div>

            <!-- خطوة 4 -->
            <div class="text-center">
                <div class="bg-indigo-100 text-indigo-600 w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                    <span class="text-xl sm:text-2xl font-bold">4</span>
                </div>
                <h3 class="text-sm sm:text-base md:text-lg font-semibold mb-1 md:mb-2">تتبع المدفوعات</h3>
                <p class="text-xs sm:text-sm text-gray-600 px-1">راقب مدفوعاتك وتقاريرك</p>
            </div>
        </div>
    </div>
</section>

<!-- قسم CTA -->
<section id="cta" class="py-12 md:py-16 lg:py-20 bg-indigo-700 text-white">
    <div class="max-w-4xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
        <div class="text-center animate-fade-in-up">
            <h2 class="text-2xl md:text-3xl font-bold mb-4 md:mb-6">جاهز لبدء رحلتك المحاسبية؟</h2>
            <p class="text-base md:text-xl mb-6 md:mb-8 opacity-90 max-w-2xl mx-auto px-2">
                انضم إلى الآلاف من الشركات التي تستخدم نظام المحاسب الذكي لإدارة أعمالها المالية
            </p>
            <a href="{{ route('register') }}"
               class="bg-white text-indigo-600 px-6 md:px-8 py-3 md:py-4 rounded-lg font-semibold hover:bg-gray-100 transition duration-300 text-sm md:text-base min-h-[52px] inline-flex items-center justify-center touch-feedback mb-3 md:mb-4">
                <i class="fas fa-check-circle ml-2"></i> ابدأ مجاناً الآن
            </a>
            <p class="text-sm opacity-75">لا يوجد التزام، يمكنك الإلغاء في أي وقت</p>
        </div>
    </div>
</section>

<!-- الفوتر -->
<footer class="bg-gray-800 text-white py-8 md:py-12 safe-bottom">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
            <!-- معلومات -->
            <div>
                <div class="flex items-center mb-3 md:mb-4">
                    <i class="fas fa-calculator text-xl md:text-2xl text-indigo-400 ml-1 md:ml-2"></i>
                    <span class="text-lg md:text-xl font-bold">المحاسب الذكي</span>
                </div>
                <p class="text-gray-400 text-sm md:text-base">
                    نظام محاسبي متكامل للشركات الصغيرة والمتوسطة، يقدم حلولاً مالية ذكية وسهلة الاستخدام.
                </p>
            </div>

            <!-- روابط سريعة -->
            <div>
                <h4 class="text-base md:text-lg font-semibold mb-3 md:mb-4">روابط سريعة</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('welcome') }}" class="text-gray-400 hover:text-white text-sm md:text-base flex items-center">
                            <i class="fas fa-home ml-1 md:ml-2 text-xs"></i> الرئيسية
                        </a>
                    </li>
                    <li>
                        <a href="#features" class="text-gray-400 hover:text-white text-sm md:text-base flex items-center">
                            <i class="fas fa-star ml-1 md:ml-2 text-xs"></i> المميزات
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="text-gray-400 hover:text-white text-sm md:text-base flex items-center">
                            <i class="fas fa-sign-in-alt ml-1 md:ml-2 text-xs"></i> تسجيل الدخول
                        </a>
                    </li>
                </ul>
            </div>

            <!-- اتصل بنا -->
            <div>
                <h4 class="text-base md:text-lg font-semibold mb-3 md:mb-4">اتصل بنا</h4>
                <ul class="space-y-2">
                    <li class="flex items-center text-gray-400 text-sm md:text-base">
                        <i class="fas fa-envelope ml-1 md:ml-2 text-xs"></i>
                        <span class="truncate">support@accounting-system.com</span>
                    </li>
                    <li class="flex items-center text-gray-400 text-sm md:text-base">
                        <i class="fas fa-phone ml-1 md:ml-2 text-xs"></i>
                        +966 500 000 000
                    </li>
                </ul>
            </div>

            <!-- وسائل التواصل -->
            <div>
                <h4 class="text-base md:text-lg font-semibold mb-3 md:mb-4">تابعنا</h4>
                <div class="flex space-x-3 md:space-x-4 space-x-reverse">
                    <a href="#" class="text-gray-400 hover:text-white touch-feedback">
                        <i class="fab fa-twitter text-lg md:text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white touch-feedback">
                        <i class="fab fa-facebook text-lg md:text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white touch-feedback">
                        <i class="fab fa-linkedin text-lg md:text-xl"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-6 md:mt-8 pt-6 md:pt-8 text-center text-gray-400 text-sm md:text-base">
            <p>© 2024 النظام المحاسبي المتعدد المستأجرين. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</footer>

<!-- JavaScript -->

<script>
    // Mobile Menu Functionality
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const closeMobileMenuButton = document.getElementById('closeMobileMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileOverlay = document.getElementById('mobileOverlay');

    function openMobileMenu() {
        mobileMenu.classList.add('open');
        mobileOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        mobileMenu.classList.remove('open');
        mobileOverlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', openMobileMenu);
    }

    if (closeMobileMenuButton) {
        closeMobileMenuButton.addEventListener('click', closeMobileMenu);
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', closeMobileMenu);
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                // Close mobile menu if open
                closeMobileMenu();

                // Smooth scroll to target
                const offset = 80; // Account for fixed header
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Touch feedback for all buttons
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('button, a.btn, .touch-feedback').forEach(element => {
            element.classList.add('touch-feedback');
        });

        // Handle orientation change
        window.addEventListener('orientationchange', function() {
            closeMobileMenu();
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                closeMobileMenu();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileMenu();
            }
        });

        // Initialize animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.card-hover').forEach(card => {
            observer.observe(card);
        });

        // Add loading state to register buttons
        const registerButtons = document.querySelectorAll('a[href="{{ route('register') }}"]');
        registerButtons.forEach(link => {
            link.addEventListener('click', function(e) {
                const originalHtml = this.innerHTML;
                this.innerHTML = `
                    <div class="loader" style="border-width: 2px; width: 20px; height: 20px; margin-left: 8px;"></div>
                    <span>جاري التوجيه...</span>
                `;
                this.style.pointerEvents = 'none';

                setTimeout(() => {
                    this.innerHTML = originalHtml;
                    this.style.pointerEvents = '';
                }, 2000);
            });
        });
    });

    // Parallax effect for hero section
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const hero = document.querySelector('.hero-gradient');

        if (hero && window.innerWidth > 768) {
            hero.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
    });

    // Back to top button
    const backToTopButton = document.createElement('button');
    backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopButton.className = 'fixed bottom-4 left-4 bg-indigo-600 text-white w-12 h-12 rounded-full shadow-lg flex items-center justify-center touch-feedback z-40 hidden';
    backToTopButton.id = 'backToTop';
    document.body.appendChild(backToTopButton);

    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.remove('hidden');
        } else {
            backToTopButton.classList.add('hidden');
        }
    });
</script>
</body>
</html>
