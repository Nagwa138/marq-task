@extends('layouts.app')

@section('title', 'لوحة التحكم - النظام المحاسبي')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-home text-indigo-600 ml-2"></i>
            مرحباً بعودتك، {{ auth()->user()->name }}! 👋
        </h1>
        <p class="text-gray-600 mt-2">إدارة شركاتك وعملائك وفواتيرك من مكان واحد</p>
    </div>

    <!-- No Companies Alert (Shown when user has no companies) -->
    @if($companies->isEmpty())
        <div class="bg-gradient-to-r from-blue-50 to-indigo-100 border border-blue-200 rounded-xl p-8 mb-8 text-center">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-building text-3xl text-blue-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-3">ابدأ رحلتك المحاسبية! 🚀</h2>
                <p class="text-gray-600 mb-6">
                    يبدو أنك لم تقم بإضافة أي شركة بعد. النظام المحاسبي المصمم لشركتك
                    يتيح لك إدارة عملائك، فواتيرك، ومدفوعاتك بكل سهولة.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('companies.create') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition duration-300 inline-flex items-center justify-center">
                        <i class="fas fa-plus-circle ml-2"></i>
                        إضافة شركتي الأولى
                    </a>
                    <button onclick="showTutorial()" class="bg-white border border-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-50 transition duration-300 inline-flex items-center justify-center">
                        <i class="fas fa-play-circle ml-2"></i>
                        شاهد شرح النظام
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- How It Works Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-800 mb-6">
            <i class="fas fa-info-circle text-indigo-600 ml-2"></i>
            كيف يعمل النظام؟
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center p-4 border rounded-lg hover:border-indigo-300 transition duration-300">
                <div class="bg-indigo-100 text-indigo-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-building text-xl"></i>
                </div>
                <h4 class="font-semibold mb-2">1. أضف شركاتك</h4>
                <p class="text-sm text-gray-600">سجل جميع شركاتك في مكان واحد</p>
            </div>

            <div class="text-center p-4 border rounded-lg hover:border-indigo-300 transition duration-300">
                <div class="bg-green-100 text-green-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <h4 class="font-semibold mb-2">2. أضف عملاءك</h4>
                <p class="text-sm text-gray-600">كل شركة لها عملاؤها الخاصون</p>
            </div>

            <div class="text-center p-4 border rounded-lg hover:border-indigo-300 transition duration-300">
                <div class="bg-purple-100 text-purple-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-invoice-dollar text-xl"></i>
                </div>
                <h4 class="font-semibold mb-2">3. أنشئ فواتير</h4>
                <p class="text-sm text-gray-600">اصدر فواتير لعملاء الشركة النشطة</p>
            </div>

            <div class="text-center p-4 border rounded-lg hover:border-indigo-300 transition duration-300">
                <div class="bg-yellow-100 text-yellow-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <h4 class="font-semibold mb-2">4. تابع التقارير</h4>
                <p class="text-sm text-gray-600">شاهد إحصائيات كل شركة على حدة</p>
            </div>
        </div>
    </div>

    <!-- Active Companies Section -->
    @if(!$companies->isEmpty())
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-building text-indigo-600 ml-2"></i>
                    شركاتي
                </h2>
                <div class="flex items-center space-x-3 space-x-reverse">
                    <span id="activeCompanyBadge" class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full hidden">
                        <i class="fas fa-check-circle ml-1"></i>
                        <span id="activeCompanyName">الشركة النشطة</span>
                    </span>
                    <a href="{{ route('companies.index') }}" class="text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-list ml-1"></i> عرض الكل
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-col-3 gap-6">
                @foreach($companies as $company)
                    <div class="company-card bg-white rounded-xl shadow-sm border p-6 {{ $company->id == session('active_company_id') ? 'active tenant-badge' : '' }}"
                         data-company-id="{{ $company->id }}"
                         data-company-name="{{ $company->name }}">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center">
                                @if($company->logo)
                                    <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="h-12 w-12 rounded-lg object-cover ml-3">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-indigo-100 flex items-center justify-center ml-3">
                                        <i class="fas fa-building text-indigo-600"></i>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $company->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $company->email }}</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                @if($company->id == session('active_company_id'))
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                <i class="fas fa-check ml-1"></i> نشطة
                            </span>
                                @else
                                    <button class="text-gray-400 hover:text-indigo-600 switch-company-btn"
                                            data-company-id="{{ $company->id }}">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Company Stats -->
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="text-center">
                                <p class="text-xs text-gray-500">العملاء</p>
                                <p class="font-bold text-gray-800">{{ $company->customers_count ?? 0 }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500">الفواتير</p>
                                <p class="font-bold text-gray-800">{{ $company->invoices_count ?? 0 }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500">الإيرادات</p>
                                <p class="font-bold text-green-600">{{ number_format($company->total_revenue ?? 0, 0) }} ر.س</p>
                            </div>
                        </div>

                        <!-- Company Actions -->
                        <div class="flex justify-between pt-4 border-t">
                            <button class="switch-company-btn text-sm text-indigo-600 hover:text-indigo-800 flex items-center"
                                    data-company-id="{{ $company->id }}">
                                <i class="fas fa-exchange-alt ml-1"></i>
                                تفعيل الشركة
                            </button>

                            <div class="flex space-x-3 space-x-reverse">
                                <a href="{{ route('customers.index') }}?company={{ $company->id }}"
                                   class="text-gray-600 hover:text-blue-600"
                                   title="عملاء الشركة">
                                    <i class="fas fa-users"></i>
                                </a>
                                <a href="{{ route('invoices.index') }}?company={{ $company->id }}"
                                   class="text-gray-600 hover:text-purple-600"
                                   title="فواتير الشركة">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                <a href="{{ route('companies.show', $company) }}"
                                   class="text-gray-600 hover:text-gray-800"
                                   title="تفاصيل الشركة">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Add New Company Card -->
                <div class="company-card bg-gradient-to-r from-gray-50 to-gray-100 border-2 border-dashed border-gray-300 rounded-xl p-6 flex flex-col items-center justify-center hover:border-indigo-300 hover:from-indigo-50 transition duration-300">
                    <div class="text-center">
                        <div class="bg-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                            <i class="fas fa-plus text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="font-semibold text-gray-700 mb-2">إضافة شركة جديدة</h3>
                        <p class="text-sm text-gray-500 mb-4">أضف شركة جديدة لإدارتها</p>
                        <a href="{{ route('companies.create') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                            <i class="fas fa-plus-circle ml-2"></i>
                            إضافة شركة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Section (Shown only when company is selected) -->
    <div id="companyStatsSection" class="{{ session('active_company_id') ? '' : 'hidden' }}">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">إجمالي العملاء</p>
                        <p class="text-2xl font-bold text-gray-800" id="statCustomers">0</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fas fa-users text-xl text-blue-600"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm text-green-600">
                        <i class="fas fa-arrow-up ml-1"></i>
                        <span>+12% عن الشهر الماضي</span>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">إجمالي الفواتير</p>
                        <p class="text-2xl font-bold text-gray-800" id="statInvoices">0</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <i class="fas fa-file-invoice-dollar text-xl text-purple-600"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm text-green-600">
                        <i class="fas fa-arrow-up ml-1"></i>
                        <span>+8% عن الشهر الماضي</span>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">إجمالي الإيرادات</p>
                        <p class="text-2xl font-bold text-gray-800" id="statRevenue">0 ر.س</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-money-bill-wave text-xl text-green-600"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm text-red-600">
                        <i class="fas fa-arrow-down ml-1"></i>
                        <span>-3% عن الشهر الماضي</span>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">فواتير متأخرة</p>
                        <p class="text-2xl font-bold text-gray-800" id="statOverdue">0</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-xl text-red-600"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm text-red-600">
                        <i class="fas fa-arrow-up ml-1"></i>
                        <span>+2 عن الأسبوع الماضي</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Revenue Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-chart-line text-indigo-600 ml-2"></i>
                    الإيرادات الشهرية
                </h3>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Invoice Status Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-pie-chart text-purple-600 ml-2"></i>
                    حالة الفواتير
                </h3>
                <div class="h-64">
                    <canvas id="invoiceStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">
                <i class="fas fa-bolt text-yellow-600 ml-2"></i>
                إجراءات سريعة للشركة النشطة
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('customers.create') }}" class="quick-action bg-blue-50 border border-blue-200 rounded-lg p-4 text-center hover:border-blue-300">
                    <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-user-plus text-blue-600"></i>
                    </div>
                    <h4 class="font-medium text-blue-800">إضافة عميل</h4>
                    <p class="text-xs text-blue-600 mt-1">للسرعة: Ctrl+Shift+C</p>
                </a>

                <a href="{{ route('invoices.create') }}" class="quick-action bg-purple-50 border border-purple-200 rounded-lg p-4 text-center hover:border-purple-300">
                    <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-file-invoice-dollar text-purple-600"></i>
                    </div>
                    <h4 class="font-medium text-purple-800">فاتورة جديدة</h4>
                    <p class="text-xs text-purple-600 mt-1">للسرعة: Ctrl+Shift+I</p>
                </a>

                <a href="{{ route('payments.create') }}" class="quick-action bg-green-50 border border-green-200 rounded-lg p-4 text-center hover:border-green-300">
                    <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-hand-holding-usd text-green-600"></i>
                    </div>
                    <h4 class="font-medium text-green-800">تسجيل دفعة</h4>
                    <p class="text-xs text-green-600 mt-1">للسرعة: Ctrl+Shift+P</p>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Invoices -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-receipt text-indigo-600 ml-2"></i>
                        أحدث الفواتير
                    </h3>
                    <a href="{{ route('invoices.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                        عرض الكل
                    </a>
                </div>
                <div class="space-y-4">
                    @foreach($recentInvoices as $invoice)
                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                            <div>
                                <a href="{{ route('invoices.show', $invoice) }}" class="font-medium text-gray-800 hover:text-indigo-600">
                                    {{ $invoice->invoice_number }}
                                </a>
                                <p class="text-sm text-gray-500">{{ $invoice->customer->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold">{{ number_format($invoice->total, 2) }} ر.س</p>
                                <span class="text-xs px-2 py-1 rounded-full {{
                                    $invoice->status == 'paid' ? 'bg-green-100 text-green-800' :
                                    ($invoice->status == 'sent' ? 'bg-blue-100 text-blue-800' :
                                    'bg-yellow-100 text-yellow-800')
                                }}">
                                    {{ $invoice->status }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Customers -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-user-clock text-green-600 ml-2"></i>
                        العملاء الجدد
                    </h3>
                    <a href="{{ route('customers.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                        عرض الكل
                    </a>
                </div>
                <div class="space-y-4">
                    @foreach($recentCustomers as $customer)
                        <div class="flex items-center p-3 border rounded-lg hover:bg-gray-50">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center ml-3">
                                <i class="fas fa-user text-indigo-600"></i>
                            </div>
                            <div class="flex-1">
                                <a href="{{ route('customers.show', $customer) }}" class="font-medium text-gray-800 hover:text-indigo-600">
                                    {{ $customer->name }}
                                </a>
                                <p class="text-sm text-gray-500">{{ $customer->email ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium {{ $customer->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ number_format($customer->balance, 2) }} ر.س
                                </p>
                                <p class="text-xs text-gray-500">{{ $customer->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- How Switching Works -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-6 mt-8">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-question-circle text-indigo-600 ml-2"></i>
            كيف يعمل تبديل الشركات؟
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-4 rounded-lg">
                <div class="flex items-center mb-3">
                    <div class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-full flex items-center justify-center ml-3">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h4 class="font-semibold">تبديل الشركة النشطة</h4>
                </div>
                <p class="text-sm text-gray-600">
                    اختر الشركة النشطة للتحكم في عملائها، فواتيرها، ومدفوعاتها.
                    يمكنك التبديل بين شركاتك بسهولة.
                </p>
            </div>

            <div class="bg-white p-4 rounded-lg">
                <div class="flex items-center mb-3">
                    <div class="bg-green-100 text-green-600 w-10 h-10 rounded-full flex items-center justify-center ml-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4 class="font-semibold">عزل البيانات</h4>
                </div>
                <p class="text-sm text-gray-600">
                    كل شركة لها بياناتها المعزولة تماماً. عملاء وفواتير ومدفوعات
                    كل شركة منفصلة عن الأخرى.
                </p>
            </div>

            <div class="bg-white p-4 rounded-lg">
                <div class="flex items-center mb-3">
                    <div class="bg-purple-100 text-purple-600 w-10 h-10 rounded-full flex items-center justify-center ml-3">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h4 class="font-semibold">إحصائيات منفصلة</h4>
                </div>
                <p class="text-sm text-gray-600">
                    كل شركة لها إحصائياتها وتقاريرها الخاصة. يمكنك مقارنة أداء
                    شركاتك المختلفة.
                </p>
            </div>
        </div>
    </div>
@endsection

@push('charts-init')
    <script>
        function initializeCharts() {
            if (!activeCompanyId) return;

            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart');
            if (revenueCtx) {
                new Chart(revenueCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                        datasets: [{
                            label: 'الإيرادات',
                            data: [12000, 19000, 15000, 25000, 22000, 30000],
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString() + ' ر.س';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Invoice Status Chart
            const invoiceCtx = document.getElementById('invoiceStatusChart');
            if (invoiceCtx) {
                new Chart(invoiceCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['مدفوعة', 'مرسلة', 'مسودة', 'متأخرة'],
                        datasets: [{
                            data: [45, 30, 15, 10],
                            backgroundColor: [
                                '#10b981',
                                '#3b82f6',
                                '#6b7280',
                                '#ef4444'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        }

        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', initializeCharts);
    </script>
@endpush
