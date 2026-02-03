{{-- resources/views/welcome.blade.php --}}
    <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>النظام المحاسبي المتعدد المستأجرين</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
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

        .feature-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-gray-50">
<!-- الهيدر -->
<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- اللوجو -->
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calculator text-2xl text-indigo-600"></i>
                    <span class="mr-2 text-xl font-bold text-gray-800">المحاسب الذكي</span>
                </div>

                <!-- قائمة التنقل -->
                <div class="hidden md:block mr-6">
                    <div class="flex space-x-4 space-x-reverse">
                        <a href="#features" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md">المميزات</a>
                        <a href="#pricing" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md">الأسعار</a>
                        <a href="#contact" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md">اتصل بنا</a>
                    </div>
                </div>
            </div>

            <!-- أزرار الدخول والتسجيل -->
            <div class="flex items-center space-x-3 space-x-reverse">
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600">
                    <i class="fas fa-sign-in-alt ml-1"></i> تسجيل الدخول
                </a>
                <a href="{{ route('register.company.form') }}"
                   class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-300">
                    <i class="fas fa-building ml-1"></i> سجل شركتك مجاناً
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- قسم البطل -->
<section class="hero-gradient text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-6">
            نظام محاسبي متكامل <br> لكل شركتك
        </h1>
        <p class="text-xl md:text-2xl mb-8 opacity-90">
            أدر فواتيرك، عملاءك، ومدفوعاتك بكل سهولة مع نظام محاسبي متعدد المستأجرين
        </p>
        <div class="flex flex-col md:flex-row justify-center gap-4">
            <a href="{{ route('register.company.form') }}"
               class="bg-white text-indigo-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition duration-300">
                <i class="fas fa-rocket ml-2"></i> ابدأ مجاناً
            </a>
            <a href="#features"
               class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-indigo-600 transition duration-300">
                <i class="fas fa-play-circle ml-2"></i> شاهد العرض التوضيحي
            </a>
        </div>
    </div>
</section>

<!-- قسم المميزات -->
<section id="features" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-4 text-gray-800">مميزات النظام</h2>
        <p class="text-gray-600 text-center mb-12 max-w-2xl mx-auto">
            نظام محاسبي متكامل مصمم خصيصاً لتلبية احتياجات الشركات الصغيرة والمتوسطة
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- ميزة 1 -->
            <div class="bg-gray-50 rounded-xl p-8 card-hover">
                <div class="feature-icon bg-indigo-100 text-indigo-600">
                    <i class="fas fa-layer-group text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4 text-gray-800">نظام متعدد المستأجرين</h3>
                <p class="text-gray-600">
                    كل شركة تحصل على بيئة معزولة تماماً ببياناتها الخاصة وإعداداتها المميزة
                </p>
            </div>

            <!-- ميزة 2 -->
            <div class="bg-gray-50 rounded-xl p-8 card-hover">
                <div class="feature-icon bg-green-100 text-green-600">
                    <i class="fas fa-file-invoice-dollar text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4 text-gray-800">إدارة الفواتير</h3>
                <p class="text-gray-600">
                    أنشئ فواتير ببنود متعددة مع حساب تلقائي للضريبة والتخفيضات
                </p>
            </div>

            <!-- ميزة 3 -->
            <div class="bg-gray-50 rounded-xl p-8 card-hover">
                <div class="feature-icon bg-blue-100 text-blue-600">
                    <i class="fas fa-hand-holding-usd text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4 text-gray-800">تسجيل المدفوعات</h3>
                <p class="text-gray-600">
                    سجل المدفوعات بطرق متعددة وتتبع حالة الفواتير تلقائياً
                </p>
            </div>

            <!-- ميزة 4 -->
            <div class="bg-gray-50 rounded-xl p-8 card-hover">
                <div class="feature-icon bg-purple-100 text-purple-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4 text-gray-800">إدارة العملاء</h3>
                <p class="text-gray-600">
                    أدر قاعدة عملائك، أرصدتهم، وتاريخ معاملاتهم بكل سهولة
                </p>
            </div>

            <!-- ميزة 5 -->
            <div class="bg-gray-50 rounded-xl p-8 card-hover">
                <div class="feature-icon bg-yellow-100 text-yellow-600">
                    <i class="fas fa-chart-bar text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4 text-gray-800">تقارير وتحليلات</h3>
                <p class="text-gray-600">
                    احصل على تقارير مالية مفصلة ولوحات تحكم تفاعلية لاتخاذ القرارات
                </p>
            </div>

            <!-- ميزة 6 -->
            <div class="bg-gray-50 rounded-xl p-8 card-hover">
                <div class="feature-icon bg-red-100 text-red-600">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4 text-gray-800">أمان عالي</h3>
                <p class="text-gray-600">
                    بياناتك محمية بطبقات أمان متعددة وعزل كامل بين الشركات
                </p>
            </div>
        </div>
    </div>
</section>

<!-- قسم كيف يعمل -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">كيف يعمل النظام؟</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- خطوة 1 -->
            <div class="text-center">
                <div class="bg-indigo-100 text-indigo-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold">1</span>
                </div>
                <h3 class="text-lg font-semibold mb-2">سجل شركتك</h3>
                <p class="text-gray-600 text-sm">أنشئ حساب شركتك في دقائق</p>
            </div>

            <!-- خطوة 2 -->
            <div class="text-center">
                <div class="bg-indigo-100 text-indigo-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold">2</span>
                </div>
                <h3 class="text-lg font-semibold mb-2">أضف عملائك</h3>
                <p class="text-gray-600 text-sm">قم بإضافة قاعدة عملائك</p>
            </div>

            <!-- خطوة 3 -->
            <div class="text-center">
                <div class="bg-indigo-100 text-indigo-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold">3</span>
                </div>
                <h3 class="text-lg font-semibold mb-2">أنشئ الفواتير</h3>
                <p class="text-gray-600 text-sm">اصدر فواتير لعملائك بسهولة</p>
            </div>

            <!-- خطوة 4 -->
            <div class="text-center">
                <div class="bg-indigo-100 text-indigo-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold">4</span>
                </div>
                <h3 class="text-lg font-semibold mb-2">تتبع المدفوعات</h3>
                <p class="text-gray-600 text-sm">راقب مدفوعاتك وتقاريرك</p>
            </div>
        </div>
    </div>
</section>

<!-- قسم CTA -->
<section class="py-20 bg-indigo-700 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-6">جاهز لبدء رحلتك المحاسبية؟</h2>
        <p class="text-xl mb-8 opacity-90">
            انضم إلى الآلاف من الشركات التي تستخدم نظام المحاسب الذكي لإدارة أعمالها المالية
        </p>
        <a href="{{ route('register.company.form') }}"
           class="bg-white text-indigo-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition duration-300 inline-block">
            <i class="fas fa-check-circle ml-2"></i> ابدأ مجاناً الآن
        </a>
        <p class="mt-4 text-sm opacity-75">لا يوجد التزام، يمكنك الإلغاء في أي وقت</p>
    </div>
</section>

<!-- الفوتر -->
<footer class="bg-gray-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- معلومات -->
            <div>
                <div class="flex items-center mb-4">
                    <i class="fas fa-calculator text-2xl text-indigo-400"></i>
                    <span class="mr-2 text-xl font-bold">المحاسب الذكي</span>
                </div>
                <p class="text-gray-400">
                    نظام محاسبي متكامل للشركات الصغيرة والمتوسطة، يقدم حلولاً مالية ذكية وسهلة الاستخدام.
                </p>
            </div>

            <!-- روابط سريعة -->
            <div>
                <h4 class="text-lg font-semibold mb-4">روابط سريعة</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('welcome') }}" class="text-gray-400 hover:text-white">الرئيسية</a></li>
                    <li><a href="#features" class="text-gray-400 hover:text-white">المميزات</a></li>
                    <li><a href="#pricing" class="text-gray-400 hover:text-white">الأسعار</a></li>
                    <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white">تسجيل الدخول</a></li>
                </ul>
            </div>

            <!-- اتصل بنا -->
            <div>
                <h4 class="text-lg font-semibold mb-4">اتصل بنا</h4>
                <ul class="space-y-2">
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-envelope ml-2"></i>
                        support@accounting-system.com
                    </li>
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-phone ml-2"></i>
                        +966 500 000 000
                    </li>
                </ul>
            </div>

            <!-- وسائل التواصل -->
            <div>
                <h4 class="text-lg font-semibold mb-4">تابعنا</h4>
                <div class="flex space-x-4 space-x-reverse">
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-linkedin text-xl"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>© 2024 النظام المحاسبي المتعدد المستأجرين. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</footer>
</body>
</html>
