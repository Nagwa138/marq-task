<aside id="sidebar" class="fixed right-0 top-16 h-screen w-64 bg-white shadow-lg transition-all duration-300 overflow-y-auto z-40">
    <div class="p-4">
        <!-- Quick Stats -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl p-4 text-white mb-6">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm opacity-90">رصيد الشهر</p>
                    <p class="text-xl font-bold">25,430 ر.س</p>
                </div>
                <i class="fas fa-chart-line text-2xl opacity-75"></i>
            </div>
            <div class="text-xs opacity-75">
                <i class="fas fa-arrow-up ml-1"></i>
                12% زيادة عن الشهر الماضي
            </div>
        </div>

        <!-- Navigation -->
        <nav class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <i class="fas fa-home ml-3 text-lg"></i>
                <span>لوحة التحكم</span>
            </a>

            <!-- Companies -->
            <div class="mt-4">
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                    <i class="fas fa-building ml-2"></i>
                    الشركات
                </p>
                <a href="{{ route('companies.index') }}"
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition-colors {{ request()->routeIs('companies.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    <i class="fas fa-list ml-3"></i>
                    <span>قائمة الشركات</span>
                    <span class="mr-auto bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full">3</span>
                </a>
                <a href="{{ route('companies.create') }}"
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition-colors">
                    <i class="fas fa-plus ml-3"></i>
                    <span>إضافة شركة</span>
                </a>
            </div>

{{--            <!-- Customers -->--}}
{{--            <div class="mt-4">--}}
{{--                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">--}}
{{--                    <i class="fas fa-users ml-2"></i>--}}
{{--                    العملاء--}}
{{--                </p>--}}
{{--                <a href="{{ route('customers.index') }}"--}}
{{--                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition-colors {{ request()->routeIs('customers.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">--}}
{{--                    <i class="fas fa-list ml-3"></i>--}}
{{--                    <span>قائمة العملاء</span>--}}
{{--                    <span class="mr-auto bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">48</span>--}}
{{--                </a>--}}
{{--                <a href="{{ route('customers.create') }}"--}}
{{--                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition-colors">--}}
{{--                    <i class="fas fa-user-plus ml-3"></i>--}}
{{--                    <span>إضافة عميل</span>--}}
{{--                </a>--}}
{{--            </div>--}}

{{--            <!-- Invoices -->--}}
{{--            <div class="mt-4">--}}
{{--                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">--}}
{{--                    <i class="fas fa-file-invoice-dollar ml-2"></i>--}}
{{--                    الفواتير--}}
{{--                </p>--}}
{{--                <a href="{{ route('invoices.index') }}"--}}
{{--                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition-colors {{ request()->routeIs('invoices.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">--}}
{{--                    <i class="fas fa-list ml-3"></i>--}}
{{--                    <span>قائمة الفواتير</span>--}}
{{--                    <span class="mr-auto bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">124</span>--}}
{{--                </a>--}}
{{--                <a href="{{ route('invoices.create') }}"--}}
{{--                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition-colors">--}}
{{--                    <i class="fas fa-plus-circle ml-3"></i>--}}
{{--                    <span>فاتورة جديدة</span>--}}
{{--                </a>--}}
{{--                <a href="{{ route('invoices.index') }}?status=overdue"--}}
{{--                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition-colors">--}}
{{--                    <i class="fas fa-exclamation-triangle ml-3 text-yellow-500"></i>--}}
{{--                    <span>فواتير متأخرة</span>--}}
{{--                    <span class="mr-auto bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">8</span>--}}
{{--                </a>--}}
{{--            </div>--}}

{{--            <!-- Payments -->--}}
{{--            <div class="mt-4">--}}
{{--                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">--}}
{{--                    <i class="fas fa-money-bill-wave ml-2"></i>--}}
{{--                    المدفوعات--}}
{{--                </p>--}}
{{--                <a href="{{ route('payments.index') }}"--}}
{{--                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition-colors {{ request()->routeIs('payments.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">--}}
{{--                    <i class="fas fa-list ml-3"></i>--}}
{{--                    <span>قائمة المدفوعات</span>--}}
{{--                </a>--}}
{{--                <a href="{{ route('payments.create') }}"--}}
{{--                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition-colors">--}}
{{--                    <i class="fas fa-hand-holding-usd ml-3"></i>--}}
{{--                    <span>تسجيل دفعة</span>--}}
{{--                </a>--}}
{{--            </div>--}}

        </nav>

        <!-- Quick Actions -->
{{--        <div class="mt-8 pt-6 border-t">--}}
{{--            <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">--}}
{{--                <i class="fas fa-bolt ml-2"></i>--}}
{{--                إجراءات سريعة--}}
{{--            </p>--}}
{{--            <div class="grid grid-cols-2 gap-2 px-4">--}}
{{--                <a href="{{ route('invoices.create') }}"--}}
{{--                   class="bg-indigo-50 text-indigo-700 p-3 rounded-lg text-center hover:bg-indigo-100 transition-colors">--}}
{{--                    <i class="fas fa-file-invoice block text-lg mb-1"></i>--}}
{{--                    <span class="text-xs">فاتورة سريعة</span>--}}
{{--                </a>--}}
{{--                <a href="{{ route('payments.quick-create') }}"--}}
{{--                   class="bg-green-50 text-green-700 p-3 rounded-lg text-center hover:bg-green-100 transition-colors">--}}
{{--                    <i class="fas fa-money-bill block text-lg mb-1"></i>--}}
{{--                    <span class="text-xs">دفعة سريعة</span>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}

        <!-- Current Company -->
        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center">
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-700">شركة التقنية المتطورة</p>
                    <p class="text-xs text-gray-500">tech.company.com</p>
                </div>
                <i class="fas fa-building text-gray-400 text-xl"></i>
            </div>
            <div class="mt-3">
                <a href="{{ route('settings.index') }}"
                   class="text-xs text-indigo-600 hover:text-indigo-800">
                    <i class="fas fa-cog ml-1"></i> تغيير الشركة
                </a>
            </div>
        </div>
    </div>
</aside>
