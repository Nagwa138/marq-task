<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }

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

        .stat-card {
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .quick-action {
            transition: all 0.3s ease;
        }

        .quick-action:hover {
            background: #f7fafc;
            transform: scale(1.05);
        }

        .progress-ring {
            transform: rotate(-90deg);
        }

        .progress-ring-circle {
            stroke-dasharray: 283;
            stroke-dashoffset: 283;
            transition: stroke-dashoffset 0.5s ease;
        }

        .tenant-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(79, 70, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
        }

        .notification-badge {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
<!-- Header -->
<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Right Side -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex items-center">
                    <i class="fas fa-calculator text-2xl text-indigo-600 ml-2"></i>
                    <a href="{{route('dashboard')}}" class="text-xl font-bold text-gray-800">المحاسب الذكي</a>
                    <span class="mr-3 text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full">المتعدد</span>
                </div>
            </div>

            <!-- Left Side -->
            <div class="flex items-center space-x-4 space-x-reverse">
                <!-- Company Selector -->
                <div class="relative" id="companySelector">
                    <button class="flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100">
                        <i class="fas fa-building ml-2"></i>
                        <span id="currentCompanyName">اختر شركة</span>
                        <i class="fas fa-chevron-down mr-2 text-sm"></i>
                    </button>

                    <!-- Companies Dropdown -->
                    <div id="companiesDropdown" class="hidden absolute left-0 mt-2 w-64 bg-white rounded-lg shadow-xl z-50 border">
                        <div class="p-4 border-b">
                            <h4 class="font-medium text-gray-800">شركاتك</h4>
                            <p class="text-sm text-gray-600">اختر شركة للتحكم في بياناتها</p>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            <!-- Companies List -->
                            <div id="companiesList" class="p-2">
                                <!-- Will be loaded via JavaScript -->
                            </div>
                        </div>
                        <div class="p-4 border-t">
                            <a href="{{ route('companies.create') }}" class="block w-full text-center bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                                <i class="fas fa-plus ml-2"></i> إضافة شركة جديدة
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="relative">
                    <button class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -left-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center notification-badge">3</span>
                    </button>
                </div>

                <!-- User Menu -->
                <!-- User Menu -->
                <div class="relative group" id="userMenu">
                    <button id="userMenuButton" class="flex items-center focus:outline-none">
                        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center ml-2">
                            <i class="fas fa-user text-indigo-600"></i>
                        </div>
                        <span class="text-gray-700 font-medium mr-2">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-gray-500 text-sm"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-xl border hidden group-hover:block hover:block z-50">
                        <div class="p-4 border-b">
                            <p class="font-medium text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-600 truncate">{{ auth()->user()->email }}</p>
                        </div>

                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-circle ml-3 text-gray-500"></i>
                                الملف الشخصي
                            </a>

                            <a href="{{ route('settings') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog ml-3 text-gray-500"></i>
                                الإعدادات
                            </a>

                            <div class="border-t my-1"></div>

                            <!-- Logout Form -->
                            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                @csrf
                                <button type="submit" class="w-full text-right flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt ml-3 text-red-500"></i>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header with Actions -->
    @hasSection('actions')
        <div class="mb-8">
            <div class="flex justify-between items-center">

                <!-- Page Actions (Alternative location) -->
                    <div class="flex space-x-3 space-x-reverse">
                        @yield('actions')
                    </div>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-white border-t mt-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-gray-600 text-sm">
                <p>النظام المحاسبي المتعدد الشركات © {{ date('Y') }}</p>
                <p class="mt-1">إصدار 2.0.0 | حالة الخدمة: <span class="text-green-600">● نشط</span></p>
            </div>
            <div class="mt-4 md:mt-0">
                <button onclick="showHelp()" class="text-sm text-gray-600 hover:text-indigo-600">
                    <i class="fas fa-question-circle ml-1"></i>
                    المساعدة والدعم
                </button>
            </div>
        </div>
    </div>
</footer>

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
        initializeCompanySelector();
        updateActiveCompanyDisplay();
        loadCompanyStats();
{{--        @stack('charts-init')--}}

        // Set up event listeners
        setupEventListeners();
        setupKeyboardShortcuts();
    });

    // Initialize company selector dropdown
    function initializeCompanySelector() {
        const selector = document.getElementById('companySelector');
        const dropdown = document.getElementById('companiesDropdown');
        const companiesList = document.getElementById('companiesList');

        // Load companies into dropdown
        loadCompaniesIntoDropdown();

        // Toggle dropdown
        selector.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!selector.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }

    // Load companies into dropdown
    function loadCompaniesIntoDropdown() {
        const companiesList = document.getElementById('companiesList');

        // Check if companies data is available
        @if(isset($companies) && $companies && $companies->isNotEmpty())
        const companies = {!! json_encode($companies, JSON_HEX_TAG) !!};
        @else
        const companies = [];
        @endif


        let html = '';
        if (companies && companies.length > 0) {
            companies.forEach(company => {
                const isActive = company.id == activeCompanyId;
                html += `
                <div class="p-2 hover:bg-gray-50 rounded-lg cursor-pointer company-dropdown-item ${isActive ? 'bg-indigo-50' : ''}"
                     data-company-id="${company.id}"
                     data-company-name="${company.name}">
                    <div class="flex items-center">
                        <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center ml-2">
                            <i class="fas fa-building text-indigo-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium">${company.name}</p>
                            <p class="text-xs text-gray-500">${company.customers_count || 0} عميل</p>
                        </div>
                        ${isActive ? '<i class="fas fa-check text-green-600"></i>' : ''}
                    </div>
                </div>
            `;
            });
        } else {
            html = `
            <div class="p-4 text-center text-gray-500">
                <i class="fas fa-building text-2xl mb-2"></i>
                <p>لا توجد شركات</p>
            </div>
        `;
        }

        if (companiesList) {
            companiesList.innerHTML = html;

            // Add click event to dropdown items
            document.querySelectorAll('.company-dropdown-item').forEach(item => {
                item.addEventListener('click', function() {
                    const companyId = this.getAttribute('data-company-id');
                    const companyName = this.getAttribute('data-company-name');
                    showSwitchModal(companyId, companyName);
                });
            });
        }

    }

    // Show switch company modal
    function showSwitchModal(companyId, companyName) {
        selectedCompanyId = companyId;
        selectedCompanyName = companyName;

        document.getElementById('modalCompanyName').textContent = companyName;
        document.getElementById('switchCompanyModal').classList.remove('hidden');
    }

    // Close switch modal
    function closeSwitchModal() {
        document.getElementById('switchCompanyModal').classList.add('hidden');
        selectedCompanyId = null;
        selectedCompanyName = null;
    }

    // Confirm company switch
    function confirmSwitchCompany() {
        if (!selectedCompanyId) return;

        // Show loading
        const button = document.querySelector('#switchCompanyModal button:last-child');
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
                    // Update active company ID
                    activeCompanyId = selectedCompanyId;
                    activeCompanyName = "{{session('active_company_name')}}"

                    // Update UI
                    updateActiveCompanyDisplay();

                    // Reload company stats
                    loadCompanyStats();

                    // Show success message
                    showNotification('تم تبديل الشركة النشطة بنجاح', 'success');

                    // Reload page after a delay
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

    // Update active company display
    function updateActiveCompanyDisplay() {
        const activeCompanyBadge = document.getElementById('activeCompanyBadge');
        const currentCompanyName = document.getElementById('currentCompanyName');
        const companyStatsSection = document.getElementById('companyStatsSection');

        if (activeCompanyId) {
            // Find company name
            let companyName = activeCompanyName ?? 'شركة غير معروفة';
            document.querySelectorAll('.company-card').forEach(card => {
                if (card.getAttribute('data-company-id') == activeCompanyId) {
                    companyName = card.getAttribute('data-company-name');
                }
            });

            // Update badge if element exists
            if (activeCompanyBadge) {
                document.getElementById('activeCompanyName').textContent = companyName;
                activeCompanyBadge.classList.remove('hidden');
            }

            // Update selector
            currentCompanyName.textContent = companyName;

            // Show stats section if it exists
            if (companyStatsSection) {
                companyStatsSection.classList.remove('hidden');
            }

            // Update active cards
            document.querySelectorAll('.company-card').forEach(card => {
                card.classList.remove('active', 'tenant-badge');
                if (card.getAttribute('data-company-id') == activeCompanyId) {
                    card.classList.add('active', 'tenant-badge');
                }
            });
        } else {
            if (activeCompanyBadge) activeCompanyBadge.classList.add('hidden');
            if (companyStatsSection) companyStatsSection.classList.add('hidden');
            currentCompanyName.textContent = 'اختر شركة';
        }
    }

    // Load company statistics
    function loadCompanyStats() {
        if (!activeCompanyId) return;

        // Check if stats elements exist
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
                    // Update stats
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

    // Setup keyboard shortcuts
    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', function(e) {
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

    // Show notification
    function showNotification(message, type = 'info') {
        const colors = {
            success: 'bg-green-100 border-green-200 text-green-800',
            error: 'bg-red-100 border-red-200 text-red-800',
            info: 'bg-blue-100 border-blue-200 text-blue-800',
            warning: 'bg-yellow-100 border-yellow-200 text-yellow-800'
        };

        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${colors[type]} px-6 py-3 rounded-lg shadow-lg z-50 flex items-center justify-between min-w-64`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} ml-2"></i>
                <span>${message}</span>
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
        showNotification('يمكنك التواصل مع الدعم عبر support@accounting.com', 'info');
    }

    // Global functions that can be used in child views
    window.showTutorial = function() {
        const tutorialModal = document.getElementById('tutorialModal');
        if (tutorialModal) {
            tutorialModal.classList.remove('hidden');
        } else {
            showNotification('عذراً، نافذة الشرح غير متوفرة حالياً', 'warning');
        }
    };

    window.closeTutorial = function() {
        const tutorialModal = document.getElementById('tutorialModal');
        if (tutorialModal) {
            tutorialModal.classList.add('hidden');
        }
    };
</script>

@stack('scripts')
</body>
</html>
