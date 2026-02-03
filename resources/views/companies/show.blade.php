@extends('layouts.app')

@section('title', $company->name)
@section('icon', 'fa-building')
@section('subtitle', 'تفاصيل الشركة والإحصائيات')

@section('actions')
    <div class="flex space-x-3 space-x-reverse">
        @if($company->id != session('active_company_id'))
            <button class=" bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 switch-company-btn"
                    data-company-id="{{ $company->id }}">
                تفعيل الشركه
            </button>
        @else
            <span class="bg-green-100 text-green-800 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-check-circle ml-2"></i>
            <span>نشطة</span>
        </span>
        @endif

{{--        <a href="#" class="bg-yellow-600 text-white px-4 py-3 rounded-lg hover:bg-yellow-700">--}}
{{--            <i class="fas fa-edit ml-2"></i> تعديل--}}
{{--        </a>--}}

        <div class="relative">
            <button id="moreActions" class="bg-gray-600 text-white px-4 py-3 rounded-lg hover:bg-gray-700">
                <i class="fas fa-ellipsis-v ml-2"></i> المزيد
            </button>
            <div id="actionMenu" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border">
                <a href="{{ route('customers.create') }}?company={{ $company->id }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user-plus ml-2"></i> إضافة عميل
                </a>
                <a href="{{ route('invoices.create') }}?company={{ $company->id }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-file-invoice ml-2"></i> فاتورة جديدة
                </a>
                <div class="border-t my-1"></div>
                <form action="{{ route('companies.destroy', $company) }}" method="POST"
                      onsubmit="return confirm('هل أنت متأكد من حذف هذه الشركة وجميع بياناتها؟')" class="block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        <i class="fas fa-trash ml-2"></i> حذف الشركة
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Company Header -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                <!-- Company Info -->
                <div class="flex items-start">
                    <!-- Logo -->
                    <div class="ml-4">
                        @if($company->logo)
                            <img src="{{ $company->logo_url }}" alt="{{ $company->name }}"
                                 class="h-20 w-20 rounded-lg object-cover border">
                        @else
                            <div class="h-20 w-20 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                                <i class="fas fa-building text-white text-3xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Details -->
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $company->name }}</h1>
                        <div class="flex flex-wrap gap-4 mt-3">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-envelope ml-2"></i>
                                <span>{{ $company->email }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-phone ml-2"></i>
                                <span>{{ $company->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt ml-2"></i>
                                <span>{{ $company->address ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-receipt ml-2"></i>
                                <span>{{ $company->tax_number ?? 'بدون رقم ضريبي' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mt-4 md:mt-0">
                <span class="px-4 py-2 rounded-full text-sm font-medium }}
                    $company->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                }}">
                    <i class="fas fa-circle text-xs {{ $company->is_active ? 'text-green-500' : 'text-gray-500' }} ml-1"></i>
                    {{ $company->is_active ? 'نشطة' : 'غير نشطة' }}
                </span>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
{{--        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">--}}
{{--            @include('components.cards.stat-card', [--}}
{{--                'title' => 'العملاء',--}}
{{--                'value' => $company->customers_count,--}}
{{--                'icon' => 'fa-users',--}}
{{--                'color' => 'blue',--}}
{{--                'trend' => '+12%',--}}
{{--                'trendUp' => true--}}
{{--            ])--}}

{{--            @include('components.cards.stat-card', [--}}
{{--                'title' => 'الفواتير',--}}
{{--                'value' => $company->invoices_count,--}}
{{--                'icon' => 'fa-file-invoice',--}}
{{--                'color' => 'purple',--}}
{{--                'trend' => '+8%',--}}
{{--                'trendUp' => true--}}
{{--            ])--}}

{{--            @include('components.cards.stat-card', [--}}
{{--                'title' => 'المبيعات',--}}
{{--                'value' => number_format($company->total_revenue ?? 0, 0) . ' ر.س',--}}
{{--                'icon' => 'fa-money-bill-wave',--}}
{{--                'color' => 'green',--}}
{{--                'trend' => '+15%',--}}
{{--                'trendUp' => true--}}
{{--            ])--}}

{{--            @include('components.cards.stat-card', [--}}
{{--                'title' => 'المتوسط',--}}
{{--                'value' => number_format($company->avg_invoice_value ?? 0, 0) . ' ر.س',--}}
{{--                'icon' => 'fa-chart-line',--}}
{{--                'color' => 'orange',--}}
{{--                'trend' => '+5%',--}}
{{--                'trendUp' => true--}}
{{--            ])--}}
{{--        </div>--}}

        <!-- Tabs Navigation -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="border-b">
                <nav class="flex -mb-px" id="companyTabs">
                    <button type="button" data-tab="overview" class="tab-btn py-4 px-6 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-home ml-2"></i>
                        نظرة عامة
                    </button>
                    <button type="button" data-tab="customers" class="tab-btn py-4 px-6 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-users ml-2"></i>
                        العملاء
                        <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full mr-2">{{ $company->customers_count }}</span>
                    </button>
                    <button type="button" data-tab="invoices" class="tab-btn py-4 px-6 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-file-invoice ml-2"></i>
                        الفواتير
                        <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full mr-2">{{ $company->invoices_count }}</span>
                    </button>
                    <button type="button" data-tab="activity" class="tab-btn py-4 px-6 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-history ml-2"></i>
                        النشاط
                    </button>
                </nav>
            </div>

            <!-- Tabs Content -->
            <div class="p-6">
                <!-- Overview Tab -->
                <div id="overviewTab" class="tab-content">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Company Details -->
                        <div class="lg:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات الشركة</h3>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 mb-2">الإعدادات</h4>
                                        <div class="space-y-3">
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-700">نسبة الضريبة:</span>
                                                <span class="font-medium">{{ $company->tax_rate ?? 15 }}%</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-700">تاريخ الإنشاء:</span>
                                                <span class="font-medium">{{ $company->created_at->format('Y-m-d') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">إحصائيات سريعة</h3>
                            <div class="space-y-4">
                                <div class="bg-white border rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-gray-600">الفواتير المدفوعة</span>
                                        <span class="text-sm font-medium text-green-600">
                                        {{ $paidInvoicesCount ?? 0 }} / {{ $company->invoices_count }}
                                    </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full"
                                             style="width: {{ $company->invoices_count > 0 ? round(($paidInvoicesCount/$company->invoices_count)*100) : 0 }}%"></div>
                                    </div>
                                </div>

                                <div class="bg-white border rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-gray-600">الفواتير المتأخرة</span>
                                        <span class="text-sm font-medium text-red-600">{{ $overdueInvoicesCount ?? 0 }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        مجموع القيمة: {{ number_format($overdueInvoicesAmount ?? 0, 2) }} ر.س
                                    </div>
                                </div>

                                <div class="bg-white border rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-gray-600">العملاء النشطين</span>
                                        <span class="text-sm font-medium text-blue-600">{{ $activeCustomersCount ?? 0 }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        متوسط التعامل: {{ number_format($avgCustomerValue ?? 0, 2) }} ر.س
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customers Tab -->
                <div id="customersTab" class="tab-content hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">عملاء الشركة</h3>
                        <a href="{{ route('customers.create') }}?company={{ $company->id }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                            <i class="fas fa-user-plus ml-2"></i> إضافة عميل
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العميل</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">معلومات التواصل</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الفواتير</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الرصيد</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($company->customers as $customer)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center ml-3">
                                                <i class="fas fa-user text-indigo-600"></i>
                                            </div>
                                            <div>
                                                <a href="{{ route('customers.show', $customer) }}" class="font-medium text-gray-900 hover:text-indigo-600">
                                                    {{ $customer->name }}
                                                </a>
                                                <p class="text-sm text-gray-500">{{ $customer->type == 'company' ? 'شركة' : 'فرد' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            <div class="text-sm text-gray-600">{{ $customer->email ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-600">{{ $customer->phone ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            <div class="text-sm">
                                                <span class="text-gray-600">الإجمالي:</span>
                                                <span class="font-medium">{{ $customer->invoices_count ?? 0 }}</span>
                                            </div>
                                            <div class="text-sm">
                                                <span class="text-gray-600">المدفوع:</span>
                                                <span class="font-medium text-green-600">{{ $customer->paid_invoices_count ?? 0 }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="{{ $customer->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                            <p class="font-bold">{{ number_format($customer->balance, 2) }} ر.س</p>
                                            <p class="text-xs">{{ $customer->balance > 0 ? 'مدين' : 'دائن' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <a href="{{ route('customers.show', $customer) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('invoices.create') }}?customer_id={{ $customer->id }}" class="text-purple-600 hover:text-purple-900">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <i class="fas fa-users text-4xl mb-4"></i>
                                            <p class="text-lg">لا يوجد عملاء لهذه الشركة</p>
                                            <p class="text-sm mt-2">ابدأ بإضافة عملاء للشركة</p>
                                            <a href="{{ route('customers.create') }}?company={{ $company->id }}" class="inline-block mt-4 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                                                <i class="fas fa-user-plus ml-2"></i> إضافة عميل
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Invoices Tab -->
                <div id="invoicesTab" class="tab-content hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">فواتير الشركة</h3>
                        <a href="{{ route('invoices.create') }}?company={{ $company->id }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                            <i class="fas fa-file-invoice-dollar ml-2"></i> فاتورة جديدة
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الفاتورة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العميل</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($company->invoices as $invoice)
                                <tr>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="font-medium text-indigo-600 hover:text-indigo-900">
                                            {{ $invoice->invoice_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="ml-3">
                                                <p class="font-medium text-gray-900">{{ $invoice->customer->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $invoice->customer->phone ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            <div class="text-sm text-gray-900">{{ $invoice->issue_date->format('Y-m-d') }}</div>
                                            <div class="text-sm text-gray-500">استحقاق: {{ $invoice->due_date->format('Y-m-d') }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ number_format($invoice->total, 2) }} ر.س</div>
                                        <div class="text-sm text-gray-500">
                                            متبقي: <span class="{{ $invoice->remaining_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format($invoice->remaining_amount, 2) }} ر.س
                                        </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium }}
                                        $invoice->status == 'paid' ? 'bg-green-100 text-green-800' :
                                        ($invoice->status == 'sent' ? 'bg-blue-100 text-blue-800' :
                                        ($invoice->status == 'overdue' ? 'bg-red-100 text-red-800' :
                                        'bg-gray-100 text-gray-800'))
                                    }}">
                                        {{ $invoice->status }}
                                    </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="text-gray-600 hover:text-gray-900">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @if($invoice->status != 'paid')
                                                <a href="{{ route('payments.create') }}?invoice_id={{ $invoice->id }}" class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-hand-holding-usd"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <i class="fas fa-file-invoice text-4xl mb-4"></i>
                                            <p class="text-lg">لا توجد فواتير لهذه الشركة</p>
                                            <p class="text-sm mt-2">ابدأ بإنشاء فواتير للشركة</p>
                                            <a href="{{ route('invoices.create') }}?company={{ $company->id }}" class="inline-block mt-4 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                                                <i class="fas fa-file-invoice-dollar ml-2"></i> فاتورة جديدة
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

{{--                <!-- Activity Tab -->--}}
{{--                <div id="activityTab" class="tab-content hidden">--}}
{{--                    <h3 class="text-lg font-semibold text-gray-800 mb-6">نشاط الشركة</h3>--}}

{{--                    <div class="space-y-4">--}}
{{--                        @forelse($activities as $activity)--}}
{{--                            <div class="bg-white border rounded-lg p-4">--}}
{{--                                <div class="flex items-start">--}}
{{--                                    <div class="ml-4">--}}
{{--                                        <div class="flex items-center">--}}
{{--                                            <i class="fas fa-{{ $activity->icon }} text-{{ $activity->color }}-600"></i>--}}
{{--                                            <span class="mr-2 font-medium text-gray-800">{{ $activity->title }}</span>--}}
{{--                                            <span class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>--}}
{{--                                        </div>--}}
{{--                                        <p class="text-gray-600 mt-2">{{ $activity->description }}</p>--}}
{{--                                        @if($activity->link)--}}
{{--                                            <a href="{{ $activity->link }}" class="text-sm text-indigo-600 hover:text-indigo-800 mt-2 inline-block">--}}
{{--                                                <i class="fas fa-external-link-alt ml-1"></i> عرض التفاصيل--}}
{{--                                            </a>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @empty--}}
{{--                            <div class="text-center py-12">--}}
{{--                                <i class="fas fa-history text-4xl text-gray-400 mb-4"></i>--}}
{{--                                <p class="text-gray-500">لا يوجد نشاط مسجل للشركة بعد</p>--}}
{{--                            </div>--}}
{{--                        @endforelse--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>

    <script>
        // Tab functionality
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');

                // Update active tab
                document.querySelectorAll('.tab-btn').forEach(b => {
                    b.classList.remove('border-indigo-500', 'text-indigo-600');
                    b.classList.add('border-transparent', 'text-gray-500');
                });
                this.classList.remove('border-transparent', 'text-gray-500');
                this.classList.add('border-indigo-500', 'text-indigo-600');

                // Show active content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });
                document.getElementById(`${tabId}Tab`).classList.remove('hidden');
            });
        });

        // Initialize first tab as active
        document.querySelector('[data-tab="overview"]').classList.add('border-indigo-500', 'text-indigo-600');

        // More actions menu
        document.getElementById('moreActions').addEventListener('click', function() {
            document.getElementById('actionMenu').classList.toggle('hidden');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            const moreActions = document.getElementById('moreActions');
            const actionMenu = document.getElementById('actionMenu');

            if (!moreActions.contains(e.target) && !actionMenu.contains(e.target)) {
                actionMenu.classList.add('hidden');
            }
        });
    </script>
@endsection
