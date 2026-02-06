<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>@yield('title', 'لوحة التحكم - النظام المحاسبي')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Chart.js -->
    @stack('charts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --header-height: 4rem;
            --sidebar-width: 280px;
            --mobile-breakpoint: 768px;
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

        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .hide-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }

        /* Touch-friendly buttons */
        button,
        a.btn,
        .clickable {
            min-height: 44px;
            min-width: 44px;
        }

        /* Company Cards */
        .company-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .company-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .company-card.active {
            border-color: #4f46e5;
            border-width: 2px;
        }

        @media (hover: none) {
            .company-card:hover {
                transform: none;
            }
        }

        /* Stat Cards */
        .stat-card {
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        /* Quick Actions */
        .quick-action {
            transition: all 0.3s ease;
        }

        .quick-action:hover {
            background: #f7fafc;
            transform: scale(1.05);
        }

        /* Animations */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(79, 70, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .tenant-badge {
            animation: pulse 2s infinite;
        }

        .notification-badge {
            animation: bounce 2s infinite;
        }

        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        /* Responsive Tables */
        .responsive-table {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .responsive-table table {
            min-width: 640px;
        }

        @media (max-width: 768px) {
            .responsive-table {
                border: 0;
            }

            .responsive-table table {
                display: block;
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

        /* Backdrop for mobile menu */
        .backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        /* Form Inputs */
        input, select, textarea {
            font-size: 16px !important; /* Prevent zoom on iOS */
        }

        /* Safe Area Insets for iOS */
        .safe-top {
            padding-top: env(safe-area-inset-top);
        }

        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }

        /* Responsive Grid */
        .responsive-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 300px), 1fr));
            gap: 1rem;
        }

        /* Mobile First Breakpoints */
        @media (min-width: 640px) {
            .sm\:responsive-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(min(100%, 300px), 1fr));
                gap: 1rem;
            }
        }

        /* Touch Feedback */
        .touch-feedback:active {
            background-color: #f3f4f6;
            transform: scale(0.98);
        }

        /* Loader */
        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #4f46e5;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen safe-top safe-bottom">
<!-- Mobile Menu Button (Hamburger) -->
<button id="mobileMenuButton" class="md:hidden fixed bottom-6 left-6 z-50 bg-indigo-600 text-white w-14 h-14 rounded-full shadow-lg flex items-center justify-center touch-feedback">
    <i class="fas fa-bars text-xl"></i>
</button>

<!-- Mobile Sidebar Overlay -->
<div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden backdrop"></div>

<!-- Mobile Sidebar -->
<div id="mobileSidebar" class="mobile-menu fixed top-0 right-0 h-full w-80 bg-white shadow-xl z-50 overflow-y-auto safe-top safe-bottom">
    <div class="p-4 border-b">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-calculator text-2xl text-indigo-600 ml-2"></i>
                <span class="text-lg font-bold text-gray-800">المحاسب الذكي</span>
            </div>
            <button id="closeMobileMenu" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <nav class="p-4">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-home ml-3"></i>
                    <span>لوحة التحكم</span>
                </a>
            </li>
            <li>
                <a href="{{ route('companies.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-building ml-3"></i>
                    <span>الشركات</span>
                </a>
            </li>
            <li>
                <a href="{{ route('customers.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-users ml-3"></i>
                    <span>العملاء</span>
                </a>
            </li>
            <li>
                <a href="{{ route('invoices.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-file-invoice ml-3"></i>
                    <span>الفواتير</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Mobile Company Selector -->
    <div class="p-4 border-t">
        <h4 class="font-medium text-gray-800 mb-3">الشركة النشطة</h4>
        <div id="mobileCompanyList" class="space-y-2">
            <!-- Companies loaded via JavaScript -->
        </div>
    </div>

    <!-- Mobile User Info -->
    <div class="p-4 border-t mt-auto">
        <div class="flex items-center">
            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center ml-3">
                <i class="fas fa-user text-indigo-600"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium">{{ auth()->user()->name }}</p>
                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100">
                <i class="fas fa-sign-out-alt ml-2"></i>
                تسجيل الخروج
            </button>
        </form>
    </div>
</div>

<!-- Main Layout -->
<div class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm z-30 relative">
        <div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 md:h-16">
                <!-- Logo (Left on mobile, center on desktop) -->
                <div class="flex items-center flex-1">
                    <!-- Mobile Logo -->
                    <a href="{{ route('dashboard') }}" class="md:hidden flex items-center">
                        <i class="fas fa-calculator text-xl text-indigo-600 ml-2"></i>
                        <span class="font-bold text-gray-800">المحاسب</span>
                    </a>

                    <!-- Desktop Logo -->
                    <div class="hidden md:flex items-center">
                        <i class="fas fa-calculator text-2xl text-indigo-600 ml-3"></i>
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">المحاسب الذكي</a>
                        <span class="mr-2 text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full hidden lg:inline">المتعدد</span>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-3 space-x-reverse">
                    <a href="{{ route('dashboard') }}" class="px-3 lg:px-4 py-2 text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-home ml-1 lg:ml-2"></i>
                        <span class="hidden lg:inline">لوحة التحكم</span>
                    </a>
                    <a href="{{ route('companies.index') }}" class="px-3 lg:px-4 py-2 text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-building ml-1 lg:ml-2"></i>
                        <span class="hidden lg:inline">الشركات</span>
                    </a>
                    <a href="{{ route('customers.index') }}" class="px-3 lg:px-4 py-2 text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-users ml-1 lg:ml-2"></i>
                        <span class="hidden lg:inline">العملاء</span>
                    </a>
                    <a href="{{ route('invoices.index') }}" class="px-3 lg:px-4 py-2 text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-file-invoice ml-1 lg:ml-2"></i>
                        <span class="hidden lg:inline">الفواتير</span>
                    </a>
                </nav>

                <!-- Right Section -->
                <div class="flex items-center space-x-2 sm:space-x-3 space-x-reverse">
                    <!-- Company Selector (Desktop) -->
                    <div class="hidden sm:block relative" id="desktopCompanySelector">
                        <button class="flex items-center px-3 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 min-w-[140px]">
                            <i class="fas fa-building ml-2 text-sm"></i>
                            <span id="currentCompanyName" class="truncate max-w-[100px]">اختر شركة</span>
                            <i class="fas fa-chevron-down mr-2 text-xs"></i>
                        </button>
                        <div id="desktopCompaniesDropdown" class="hidden absolute left-0 mt-2 w-64 bg-white rounded-lg shadow-xl z-50 border">
                            <!-- Desktop dropdown content -->
                        </div>
                    </div>

                    <!-- Company Selector (Mobile - Icon only) -->
                    <div class="sm:hidden relative">
                        <button id="mobileCompanyButton" class="text-gray-600 hover:text-indigo-600 p-2">
                            <i class="fas fa-building text-xl"></i>
                        </button>
                    </div>

                    <!-- Notifications -->
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700 p-2">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -left-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center notification-badge">3</span>
                        </button>
                    </div>

                    <!-- User Menu (Desktop) -->
                    <div class="hidden md:flex items-center space-x-2 space-x-reverse">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center ml-2">
                                <i class="fas fa-user text-indigo-600"></i>
                            </div>
                            <span class="text-gray-700 font-medium hidden lg:inline">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Active Company Badge (Mobile) -->
    <div id="mobileActiveCompany" class="bg-indigo-50 border-b border-indigo-100 py-2 px-4 md:hidden">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-building text-indigo-600 ml-2 text-sm"></i>
                <span id="mobileCompanyName" class="text-sm font-medium text-gray-700">اختر شركة</span>
            </div>
            <button onclick="showMobileCompanySelector()" class="text-indigo-600 text-sm">
                <i class="fas fa-exchange-alt ml-1"></i>
                تبديل
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1">
        <!-- Page Header -->
        @hasSection('page-header')
            <div class="bg-white border-b">
                <div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-4 md:py-6">
                    @yield('page-header')
                </div>
            </div>
        @endif

        <!-- Content Area -->
        <div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-4 md:py-8">
            <!-- Quick Actions (Mobile) -->
            <div class="md:hidden mb-6">
                <div class="flex items-center space-x-3 space-x-reverse overflow-x-auto pb-2 hide-scrollbar">
                    <a href="{{ route('companies.create') }}" class="flex-shrink-0 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-plus ml-2"></i>
                        شركة
                    </a>
                    <a href="{{ route('customers.create') }}" class="flex-shrink-0 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-user-plus ml-2"></i>
                        عميل
                    </a>
                    <a href="{{ route('invoices.create') }}" class="flex-shrink-0 bg-purple-600 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-file-invoice ml-2"></i>
                        فاتورة
                    </a>
                </div>
            </div>

            <!-- Page Actions -->
            @hasSection('actions')
                <div class="mb-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                        <div class="order-2 sm:order-1">
                            @yield('actions')
                        </div>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-auto">
        <div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-gray-600 text-sm text-center md:text-right">
                    <p>النظام المحاسبي المتعدد الشركات © {{ date('Y') }}</p>
                    <p class="mt-1 hidden sm:block">إصدار 2.0.0 | حالة الخدمة: <span class="text-green-600">● نشط</span></p>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <button onclick="showHelp()" class="text-sm text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-question-circle ml-1"></i>
                        <span class="hidden sm:inline">المساعدة</span>
                    </button>
                    <a href="tel:+966123456789" class="text-sm text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-phone ml-1"></i>
                        <span class="hidden sm:inline">الدعم</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- Mobile Company Selector Modal -->
<div id="mobileCompanyModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden backdrop">
    <div class="fixed bottom-0 right-0 left-0 bg-white rounded-t-2xl safe-bottom">
        <div class="p-4 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">اختر شركة</h3>
                <button onclick="closeMobileCompanyModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="max-h-60 overflow-y-auto p-4">
            <div id="mobileCompaniesList" class="space-y-3">
                <!-- Companies loaded via JavaScript -->
            </div>
        </div>
        <div class="p-4 border-t">
            <a href="{{ route('companies.create') }}" class="block w-full text-center bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-medium">
                <i class="fas fa-plus ml-2"></i> إضافة شركة جديدة
            </a>
        </div>
    </div>
</div>

<!-- Switch Company Modal -->
<div id="switchCompanyModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">تبديل الشركة النشطة</h3>
                <p class="text-gray-600 mb-4">هل تريد تفعيل الشركة <span id="modalCompanyName" class="font-medium"></span>؟</p>
                <p class="text-sm text-gray-500">سيتم تحميل عملاء وفواتير هذه الشركة</p>
            </div>
            <div class="p-6 border-t flex justify-end space-x-3 space-x-reverse">
                <button onclick="closeSwitchModal()" class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    إلغاء
                </button>
                <button onclick="confirmSwitchCompany()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-exchange-alt ml-2"></i> تبديل
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modals Section -->
@includeWhen(View::exists('partials.modals'), 'partials.modals')
@stack('modals')

<!-- JavaScript -->
<script>
    // Global variables
    let selectedCompanyId = null;
    let selectedCompanyName = null;
    let activeCompanyId = {{ session('active_company_id') ?? 'null' }};
    let activeCompanyName = "{{ session('active_company_name') ?? null }}";

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeMobileMenu();
        initializeCompanySelectors();
        updateActiveCompanyDisplay();
        loadCompanyStats();

        // Set up event listeners
        setupEventListeners();
        setupKeyboardShortcuts();
        setupTouchEvents();

        // Handle orientation change
        window.addEventListener('orientationchange', handleOrientationChange);

        // Handle resize
        window.addEventListener('resize', debounce(handleResize, 250));
    });

    // Initialize mobile menu
    function initializeMobileMenu() {
        const menuButton = document.getElementById('mobileMenuButton');
        const closeButton = document.getElementById('closeMobileMenu');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');

        if (menuButton && mobileSidebar) {
            menuButton.addEventListener('click', function() {
                mobileSidebar.classList.add('open');
                mobileOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });

            closeButton.addEventListener('click', closeMobileMenu);
            mobileOverlay.addEventListener('click', closeMobileMenu);
        }
    }

    function closeMobileMenu() {
        const mobileSidebar = document.getElementById('mobileSidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');

        if (mobileSidebar) {
            mobileSidebar.classList.remove('open');
            mobileOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    // Initialize company selectors
    function initializeCompanySelectors() {
        initializeDesktopCompanySelector();
        initializeMobileCompanySelector();
        loadCompanies();
    }

    function initializeDesktopCompanySelector() {
        const selector = document.getElementById('desktopCompanySelector');
        const dropdown = document.getElementById('desktopCompaniesDropdown');

        if (selector && dropdown) {
            selector.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!selector.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }
    }

    function initializeMobileCompanySelector() {
        const mobileButton = document.getElementById('mobileCompanyButton');
        const modal = document.getElementById('mobileCompanyModal');

        if (mobileButton && modal) {
            mobileButton.addEventListener('click', function() {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        }
    }

    function closeMobileCompanyModal() {
        const modal = document.getElementById('mobileCompanyModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    function showMobileCompanySelector() {
        const modal = document.getElementById('mobileCompanyModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    // Load companies into all selectors
    function loadCompanies() {
        @if(isset($companies) && $companies && $companies->isNotEmpty())
        const companies = {!! json_encode($companies, JSON_HEX_TAG) !!};
        @else
        const companies = [];
        @endif

        updateDesktopCompanyDropdown(companies);
        updateMobileCompanyList(companies);
        updateMobileSidebarCompanies(companies);
    }

    function updateDesktopCompanyDropdown(companies) {
        const dropdown = document.getElementById('desktopCompaniesDropdown');
        if (!dropdown) return;

        let html = '';
        if (companies && companies.length > 0) {
            html += `
                <div class="p-3 border-b">
                    <h4 class="font-medium text-gray-800">شركاتك</h4>
                    <p class="text-sm text-gray-600">اختر شركة للتحكم في بياناتها</p>
                </div>
                <div class="max-h-64 overflow-y-auto">
            `;

            companies.forEach(company => {
                const isActive = company.id == activeCompanyId;
                html += `
                    <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 company-dropdown-item ${isActive ? 'bg-indigo-50' : ''}"
                         data-company-id="${company.id}"
                         data-company-name="${company.name}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center ml-2">
                                    <i class="fas fa-building text-indigo-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-sm">${company.name}</p>
                                    <p class="text-xs text-gray-500">${company.customers_count || 0} عميل</p>
                                </div>
                            </div>
                            ${isActive ? '<i class="fas fa-check text-green-600"></i>' : ''}
                        </div>
                    </div>
                `;
            });

            html += '</div>';
        }

        dropdown.innerHTML = html;

        // Add click events
        document.querySelectorAll('#desktopCompaniesDropdown .company-dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                const companyId = this.getAttribute('data-company-id');
                const companyName = this.getAttribute('data-company-name');
                showSwitchModal(companyId, companyName);
                dropdown.classList.add('hidden');
            });
        });
    }

    function updateMobileCompanyList(companies) {
        const mobileList = document.getElementById('mobileCompaniesList');
        if (!mobileList) return;

        let html = '';
        if (companies && companies.length > 0) {
            companies.forEach(company => {
                const isActive = company.id == activeCompanyId;
                html += `
                    <div class="p-4 border rounded-lg cursor-pointer company-mobile-item ${isActive ? 'border-indigo-300 bg-indigo-50' : 'border-gray-200'}"
                         data-company-id="${company.id}"
                         data-company-name="${company.name}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-lg ${isActive ? 'bg-indigo-100' : 'bg-gray-100'} flex items-center justify-center ml-2">
                                    <i class="fas fa-building ${isActive ? 'text-indigo-600' : 'text-gray-400'}"></i>
                                </div>
                                <div>
                                    <p class="font-medium">${company.name}</p>
                                    <p class="text-sm text-gray-500">${company.customers_count || 0} عميل | ${company.invoices_count || 0} فاتورة</p>
                                </div>
                            </div>
                            ${isActive ? '<span class="text-green-600 text-sm"><i class="fas fa-check"></i> نشطة</span>' : ''}
                        </div>
                    </div>
                `;
            });
        } else {
            html = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-building text-3xl mb-3"></i>
                    <p>لا توجد شركات</p>
                </div>
            `;
        }

        mobileList.innerHTML = html;

        // Add click events
        document.querySelectorAll('#mobileCompaniesList .company-mobile-item').forEach(item => {
            item.addEventListener('click', function() {
                const companyId = this.getAttribute('data-company-id');
                const companyName = this.getAttribute('data-company-name');
                closeMobileCompanyModal();
                showSwitchModal(companyId, companyName);
            });
        });
    }

    function updateMobileSidebarCompanies(companies) {
        const sidebarList = document.getElementById('mobileCompanyList');
        if (!sidebarList) return;

        let html = '';
        if (companies && companies.length > 0) {
            companies.forEach(company => {
                const isActive = company.id == activeCompanyId;
                html += `
                    <div class="flex items-center p-3 ${isActive ? 'bg-indigo-50 border border-indigo-200' : 'border border-gray-200'} rounded-lg">
                        <div class="h-8 w-8 rounded-lg ${isActive ? 'bg-indigo-100' : 'bg-gray-100'} flex items-center justify-center ml-2">
                            <i class="fas fa-building ${isActive ? 'text-indigo-600' : 'text-gray-400'}"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-sm">${company.name}</p>
                            <p class="text-xs text-gray-500">${company.customers_count || 0} عميل</p>
                        </div>
                    </div>
                `;
            });
        }

        sidebarList.innerHTML = html;
    }

    // Show switch company modal
    function showSwitchModal(companyId, companyName) {
        selectedCompanyId = companyId;
        selectedCompanyName = companyName;

        const modal = document.getElementById('switchCompanyModal');
        const companyNameElement = document.getElementById('modalCompanyName');

        if (modal && companyNameElement) {
            companyNameElement.textContent = companyName;
            modal.classList.remove('hidden');
        }
    }

    // Close switch modal
    function closeSwitchModal() {
        const modal = document.getElementById('switchCompanyModal');
        if (modal) {
            modal.classList.add('hidden');
        }
        selectedCompanyId = null;
        selectedCompanyName = null;
    }

    // Confirm company switch
    function confirmSwitchCompany() {
        if (!selectedCompanyId) return;

        // Show loading
        const button = document.querySelector('#switchCompanyModal button:last-child');
        if (button) {
            const originalHtml = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري التبديل...';
            button.disabled = true;

            // Send AJAX request to switch company
            fetch('{{ route("company.switch") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    company_id: selectedCompanyId
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        activeCompanyId = selectedCompanyId;
                        activeCompanyName = selectedCompanyName;

                        updateActiveCompanyDisplay();
                        loadCompanyStats();

                        showNotification('تم تبديل الشركة النشطة بنجاح', 'success');

                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showNotification('حدث خطأ أثناء تبديل الشركة', 'error');
                    }
                })
                .catch(error => {
                    showNotification('حدث خطأ في الاتصال', 'error');
                })
                .finally(() => {
                    button.innerHTML = originalHtml;
                    button.disabled = false;
                    closeSwitchModal();
                });
        }
    }

    // Update active company display
    function updateActiveCompanyDisplay() {
        // Update desktop selector
        const currentCompanyName = document.getElementById('currentCompanyName');
        const mobileCompanyName = document.getElementById('mobileCompanyName');

        if (currentCompanyName) {
            currentCompanyName.textContent = activeCompanyName || 'اختر شركة';
        }

        if (mobileCompanyName) {
            mobileCompanyName.textContent = activeCompanyName || 'اختر شركة';
        }

        // Update mobile active company badge visibility
        const mobileActiveCompany = document.getElementById('mobileActiveCompany');
        if (mobileActiveCompany) {
            if (activeCompanyId) {
                mobileActiveCompany.classList.remove('hidden');
            } else {
                mobileActiveCompany.classList.add('hidden');
            }
        }
    }

    // Load company statistics
    function loadCompanyStats() {
        if (!activeCompanyId) return;

        const statElements = document.querySelectorAll('[id^="stat"]');
        if (statElements.length === 0) return;

        // Show loading state
        statElements.forEach(stat => {
            stat.classList.add('loading-shimmer');
            stat.textContent = '...';
        });

        // AJAX request to load company stats
        fetch(`/api/companies/${activeCompanyId}/stats`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const customersEl = document.getElementById('statCustomers');
                    const invoicesEl = document.getElementById('statInvoices');
                    const revenueEl = document.getElementById('statRevenue');
                    const overdueEl = document.getElementById('statOverdue');

                    if (customersEl) customersEl.textContent = data.stats.customers || 0;
                    if (invoicesEl) invoicesEl.textContent = data.stats.invoices || 0;
                    if (revenueEl) revenueEl.textContent = (data.stats.revenue || 0).toLocaleString() + ' ر.س';
                    if (overdueEl) overdueEl.textContent = data.stats.overdue || 0;

                    // Remove loading
                    statElements.forEach(stat => {
                        stat.classList.remove('loading-shimmer');
                    });
                }
            })
            .catch(error => {
                console.error('Error loading company stats:', error);
            });
    }

    // Setup event listeners
    function setupEventListeners() {
        // Switch company buttons
        document.querySelectorAll('.switch-company-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const companyId = this.getAttribute('data-company-id');
                const companyCard = this.closest('.company-card');
                const companyName = companyCard.getAttribute('data-company-name');
                showSwitchModal(companyId, companyName);
            });
        });

        // Company card click
        document.querySelectorAll('.company-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('a') && !e.target.closest('button')) {
                    const companyId = this.getAttribute('data-company-id');
                    const companyName = this.getAttribute('data-company-name');
                    if (companyId != activeCompanyId) {
                        showSwitchModal(companyId, companyName);
                    }
                }
            });
        });
    }

    // Setup touch events
    function setupTouchEvents() {
        // Add touch feedback to buttons
        document.querySelectorAll('button, a.btn, .clickable').forEach(element => {
            element.classList.add('touch-feedback');
        });
    }

    // Setup keyboard shortcuts
    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', function(e) {
            // Escape key closes modals
            if (e.key === 'Escape') {
                closeSwitchModal();
                closeMobileMenu();
                closeMobileCompanyModal();
            }

            // Ctrl+Shift+C: Add customer
            if (e.ctrlKey && e.shiftKey && e.key === 'C') {
                e.preventDefault();
                window.location.href = '{{ route("customers.create") }}';
            }

            // Ctrl+Shift+I: Add invoice
            if (e.ctrlKey && e.shiftKey && e.key === 'I') {
                e.preventDefault();
                window.location.href = '{{ route("invoices.create") }}';
            }
        });
    }

    // Handle orientation change
    function handleOrientationChange() {
        // Close modals on orientation change
        closeMobileMenu();
        closeMobileCompanyModal();
        closeSwitchModal();

        // Update viewport
        setTimeout(() => {
            window.scrollTo(0, 0);
        }, 100);
    }

    // Handle window resize
    function handleResize() {
        // If window is resized to desktop size, close mobile menu
        if (window.innerWidth >= 768) {
            closeMobileMenu();
            closeMobileCompanyModal();
        }
    }

    // Utility: Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Show notification
    function showNotification(message, type = 'info') {
        const colors = {
            success: 'bg-green-100 border-green-200 text-green-800',
            error: 'bg-red-100 border-red-200 text-red-800',
            info: 'bg-blue-100 border-blue-200 text-blue-800',
            warning: 'bg-yellow-100 border-yellow-200 text-yellow-800'
        };

        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${colors[type]} px-6 py-3 rounded-lg shadow-lg z-50 flex items-center justify-between min-w-[200px] sm:min-w-64`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} ml-2"></i>
                <span class="text-sm sm:text-base">${message}</span>
            </div>
            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Show help
    function showHelp() {
        showNotification('يمكنك التواصل مع الدعم عبر support@accounting.com أو الاتصال على ٠٥٠١٢٣٤٥٦٧', 'info');
    }

    // Global functions
    window.showTutorial = function() {
        showNotification('ستتوفر الدروس قريباً في قسم التعليمات', 'info');
    };

    window.closeTutorial = function() {
        // Implementation depends on your tutorial modal
    };
</script>

@stack('scripts')
</body>
</html>
