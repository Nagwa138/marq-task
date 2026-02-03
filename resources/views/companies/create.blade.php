@extends('layouts.app')

@section('title', 'إضافة شركة جديدة')
@section('icon', 'fa-building')
@section('subtitle', 'أضف شركتك لإدارتها في النظام المحاسبي')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-gray-700 hover:text-indigo-600">
                        <i class="fas fa-home ml-2"></i>
                        لوحة التحكم
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-left text-gray-400"></i>
                        <a href="{{ route('companies.index') }}" class="mr-1 text-sm font-medium text-gray-700 hover:text-indigo-600">
                            الشركات
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-left text-gray-400"></i>
                        <span class="mr-1 text-sm font-medium text-indigo-600">إضافة جديدة</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm">
            <!-- Header -->
            <div class="px-6 py-4 border-b">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">بيانات الشركة الأساسية</h2>
                        <p class="text-gray-600 text-sm mt-1">املأ البيانات المطلوبة لإضافة شركتك</p>
                    </div>
                    <div class="flex items-center">
                    <span class="text-xs bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full">
                        <i class="fas fa-info-circle ml-1"></i> جميع الحقول مطلوبة ما لم يذكر خلاف ذلك
                    </span>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data" id="companyForm">
                @csrf

                <div class="p-6">
                    @include('companies.partials.company-form')
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t bg-gray-50 rounded-b-xl">
                    <div class="flex justify-between items-center">
                        <div>
                            <button type="button" onclick="window.history.back()" class="px-6 py-3 text-gray-700 hover:bg-gray-200 rounded-lg font-medium">
                                <i class="fas fa-arrow-right ml-2"></i>
                                إلغاء والعودة
                            </button>
                        </div>
                        <div class="flex space-x-3 space-x-reverse">
                            <button type="button" onclick="saveAsDraft()" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium">
                                <i class="fas fa-save ml-2"></i>
                                حفظ كمسودة
                            </button>
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 font-medium">
                                <i class="fas fa-check-circle ml-2"></i>
                                حفظ الشركة
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Help Card -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start">
                <i class="fas fa-lightbulb text-2xl text-blue-600 ml-4 mt-1"></i>
                <div>
                    <h4 class="font-semibold text-blue-800 mb-2">نصائح لإضافة شركة جديدة</h4>
                    <ul class="text-blue-700 space-y-2 text-sm">
                        <li class="flex items-start">
                            <i class="fas fa-check ml-2 mt-1 text-green-500"></i>
                            <span>يجب أن يكون اسم الشركة فريداً داخل حسابك</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check ml-2 mt-1 text-green-500"></i>
                            <span>يمكنك إضافة الشعار لاحقاً من صفحة الإعدادات</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check ml-2 mt-1 text-green-500"></i>
                            <span>الرقم الضريبي مهم لإصدار الفواتير الضريبية</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check ml-2 mt-1 text-green-500"></i>
                            <span>بعد الإضافة، يمكنك تفعيل الشركة للبدء في إدارة بياناتها</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function saveAsDraft() {
            const form = document.getElementById('companyForm');
            const draftInput = document.createElement('input');
            draftInput.type = 'hidden';
            draftInput.name = 'is_active';
            draftInput.value = '0';
            form.appendChild(draftInput);
            form.submit();
        }

        // Preview logo before upload
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logoPreview').innerHTML = `
                    <img src="${e.target.result}" class="h-32 w-32 rounded-lg object-cover">
                `;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
