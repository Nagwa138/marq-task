<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - النظام المحاسبي</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            animation: fadeIn 0.5s ease-out;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .input-group {
            transition: all 0.3s ease;
        }

        .input-group:focus-within {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-size: 200% 100%;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-position: 100% 0;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .password-toggle {
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #4f46e5;
        }

        .logo-glow {
            text-shadow: 0 0 20px rgba(102, 126, 234, 0.5);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* تحسينات للأجهزة المحمولة */
        @media (max-width: 640px) {
            .login-card {
                margin: 1rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body class="p-4">
<div class="login-container w-full max-w-md">
    <!-- البطاقة الرئيسية -->
    <div class="login-card rounded-2xl p-8">
        <!-- الشعار والعنوان -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 mb-4">
                <i class="fas fa-calculator text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 logo-glow">
                النظام المحاسبي الذكي
            </h1>
            <p class="text-gray-600 mt-2">أدخل بيانات الدخول للوصول إلى حسابك</p>
        </div>

        <!-- رسائل الخطأ/النجاح -->
        @if(session('status'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle ml-2"></i>
                    <span>{{ session('status') }}</span>
                </div>
                <button type="button" class="text-green-700 hover:text-green-900" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-circle ml-2"></i>
                    <span class="font-medium">حدث خطأ</span>
                </div>
                <ul class="list-disc mr-4 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- نموذج تسجيل الدخول -->
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="space-y-6">
                <!-- حقل البريد الإلكتروني -->
                <div class="input-group">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope text-gray-400 ml-2"></i>
                        البريد الإلكتروني
                    </label>
                    <div class="relative">
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="email"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-300"
                               placeholder="example@company.com"
                               oninvalid="this.setCustomValidity('الرجاء إدخال بريد إلكتروني صحيح')"
                               oninput="this.setCustomValidity('')">
                        <div class="absolute left-3 top-3.5">
                            <i class="fas fa-at text-gray-400"></i>
                        </div>
                    </div>
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل كلمة المرور -->
                <div class="input-group">
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-lock text-gray-400 ml-2"></i>
                            كلمة المرور
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-key ml-1"></i>
                                نسيت كلمة المرور؟
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <input type="password"
                               name="password"
                               id="password"
                               required
                               autocomplete="current-password"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition duration-300"
                               placeholder="••••••••"
                               oninvalid="this.setCustomValidity('الرجاء إدخال كلمة المرور')"
                               oninput="this.setCustomValidity('')">
                        <div class="absolute left-3 top-3.5">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <button type="button"
                                id="togglePassword"
                                class="password-toggle absolute left-10 top-3.5 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- تذكرني وفحص الروبوت -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox"
                               name="remember"
                               id="remember"
                               {{ old('remember') ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember" class="mr-2 text-sm text-gray-700">
                            تذكرني على هذا الجهاز
                        </label>
                    </div>

                    <!-- Simple CAPTCHA -->
                    <div class="flex items-center">
                        <input type="checkbox"
                               name="human"
                               id="human"
                               required
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="human" class="mr-2 text-sm text-gray-700">
                            أنا لست روبوتاً
                        </label>
                    </div>
                </div>

                <!-- زر تسجيل الدخول -->
                <button type="submit"
                        id="loginButton"
                        class="btn-primary w-full text-white py-3 px-4 rounded-lg font-semibold flex items-center justify-center">
                    <i class="fas fa-sign-in-alt ml-2" id="loginIcon"></i>
                    <span id="loginText">تسجيل الدخول</span>
                </button>

                <!-- تسجيل الدخول بواسطة -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">أو</span>
                    </div>
                </div>

                <!-- أزرار تسجيل الدخول البديلة -->
                <div class="grid grid-cols-2 gap-3">
                    <button type="button"
                            class="bg-white border border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-50 transition duration-300 flex items-center justify-center">
                        <i class="fab fa-google text-red-500 ml-2"></i>
                        <span>Google</span>
                    </button>
                    <button type="button"
                            class="bg-white border border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-50 transition duration-300 flex items-center justify-center">
                        <i class="fab fa-microsoft text-blue-500 ml-2"></i>
                        <span>Microsoft</span>
                    </button>
                </div>
            </div>
        </form>

        <!-- روابط إضافية -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="text-center space-y-4">
                <!-- عرض النظام -->
                <a href="{{ route('welcome') }}?demo=true"
                   class="inline-flex items-center text-gray-600 hover:text-indigo-600">
                    <i class="fas fa-eye ml-2"></i>
                    <span>تصفح النظام كضيف</span>
                </a>

                <!-- تسجيل جديد -->
                <div class="text-sm text-gray-600">
                    ليس لديك حساب؟
                    <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                        <i class="fas fa-user-plus ml-1"></i> إنشاء حساب جديد
                    </a>
                </div>

                <!-- العودة للرئيسية -->
                <div>
                    <a href="{{ route('welcome') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-arrow-right ml-2"></i>
                        العودة للصفحة الرئيسية
                    </a>
                </div>
            </div>
        </div>

        <!-- معلومات الأمان -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-center text-xs text-gray-500">
                <i class="fas fa-shield-alt ml-1"></i>
                <span>جميع الاتصالات مشفرة باستخدام SSL 256-bit</span>
            </div>
        </div>
    </div>

    <!-- معلومات إضافية -->
    <div class="text-center mt-6 text-white/80 text-sm">
        <p>© {{ date('Y') }} النظام المحاسبي الذكي. جميع الحقوق محفوظة.</p>
        <p class="mt-1">الإصدار 2.0.0</p>
    </div>
</div>

<!-- JavaScript -->
<script>
    // إظهار/إخفاء كلمة المرور
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const passwordIcon = document.getElementById('passwordIcon');

    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        passwordIcon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    });

    // إدارة حالة زر تسجيل الدخول
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const loginIcon = document.getElementById('loginIcon');
    const loginText = document.getElementById('loginText');

    loginForm.addEventListener('submit', function(e) {
        // التحقق من CAPTCHA
        const humanCheck = document.getElementById('human');
        if (!humanCheck.checked) {
            e.preventDefault();
            alert('الرجاء التأكيد أنك لست روبوتاً');
            humanCheck.focus();
            return;
        }

        // تغيير حالة الزر
        loginButton.disabled = true;
        loginIcon.className = 'fas fa-spinner fa-spin ml-2';
        loginText.textContent = 'جاري التحقق...';
        loginButton.classList.remove('hover:bg-indigo-700');
    });

    // Auto-focus على حقل البريد الإلكتروني
    document.addEventListener('DOMContentLoaded', function() {
        const emailField = document.getElementById('email');
        if (emailField) {
            emailField.focus();

            // If there's a demo parameter, pre-fill demo credentials
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('demo') === 'true') {
                emailField.value = 'demo@accounting.com';
                password.value = 'demopassword123';
            }
        }
    });

    // Validate email format
    const emailField = document.getElementById('email');
    emailField.addEventListener('blur', function() {
        const email = this.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email && !emailRegex.test(email)) {
            this.setCustomValidity('الرجاء إدخال بريد إلكتروني صحيح');
            this.reportValidity();
        } else {
            this.setCustomValidity('');
        }
    });

    // Show password strength (optional)
    password.addEventListener('input', function() {
        const strengthIndicator = document.getElementById('passwordStrength');
        if (strengthIndicator) {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            strengthIndicator.className = `h-1 rounded-full ${strength === 0 ? 'bg-gray-200 w-0' :
                strength === 1 ? 'bg-red-500 w-1/4' :
                    strength === 2 ? 'bg-yellow-500 w-1/2' :
                        strength === 3 ? 'bg-blue-500 w-3/4' :
                            'bg-green-500 w-full'}`;
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+Enter to submit
        if (e.ctrlKey && e.key === 'Enter') {
            loginForm.submit();
        }

        // Esc to clear form
        if (e.key === 'Escape') {
            loginForm.reset();
            emailField.focus();
        }
    });

    // Demo login
    function fillDemoCredentials() {
        emailField.value = 'demo@accounting.com';
        password.value = 'demopassword123';
        document.getElementById('remember').checked = true;
        document.getElementById('human').checked = true;

        // Show success message
        const demoAlert = document.createElement('div');
        demoAlert.className = 'mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg flex items-center justify-between';
        demoAlert.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-info-circle ml-2"></i>
                    <span>تم تعبئة بيانات تجريبية. اضغط تسجيل الدخول للبدء.</span>
                </div>
                <button type="button" class="text-blue-700 hover:text-blue-900" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;

        const form = loginForm;
        form.parentNode.insertBefore(demoAlert, form);
    }

    // Add password strength indicator if needed
    const passwordGroup = document.querySelector('.input-group:nth-child(2)');
    if (passwordGroup) {
        const strengthDiv = document.createElement('div');
        strengthDiv.className = 'mt-2';
        strengthDiv.innerHTML = `
                <div class="flex justify-between mb-1">
                    <span class="text-xs text-gray-500">قوة كلمة المرور</span>
                    <span id="strengthText" class="text-xs text-gray-500">ضعيفة</span>
                </div>
                <div class="h-1 rounded-full bg-gray-200" id="passwordStrength"></div>
            `;
        passwordGroup.appendChild(strengthDiv);
    }
</script>
</body>
</html>
