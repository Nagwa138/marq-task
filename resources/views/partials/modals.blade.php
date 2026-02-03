<div id="switchCompanyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-exchange-alt text-indigo-600 ml-2"></i>
                    تبديل الشركة النشطة
                </h3>
            </div>

            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center ml-3">
                        <i class="fas fa-building text-indigo-600"></i>
                    </div>
                    <div>
                        <p class="font-medium" id="modalCompanyName">اسم الشركة</p>
                        <p class="text-sm text-gray-500">سيتم تحميل بيانات هذه الشركة</p>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl ml-3"></i>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">ملاحظة هامة</p>
                            <p class="text-xs text-yellow-700 mt-1">
                                عند تبديل الشركة النشطة، سيتم تحميل عملاء وفواتير ومدفوعات
                                هذه الشركة فقط. يمكنك التبديل بين الشركات في أي وقت.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t flex justify-end space-x-3 space-x-reverse">
                <button onclick="closeSwitchModal()" class="px-6 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    إلغاء
                </button>
                <button onclick="confirmSwitchCompany()" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-exchange-alt ml-2"></i>
                    تبديل الشركة
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tutorial Modal -->
<div id="tutorialModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-graduation-cap text-indigo-600 ml-2"></i>
                    دليل استخدام النظام
                </h3>
            </div>

            <div class="p-6">
                <div class="space-y-6">
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">1. إضافة شركاتك</h4>
                        <p class="text-gray-600 text-sm">
                            ابدأ بإضافة شركاتك عبر زر "إضافة شركة". كل شركة تمثل كياناً مستقلاً.
                        </p>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">2. تفعيل الشركة</h4>
                        <p class="text-gray-600 text-sm">
                            انقر على أي شركة لتفعيلها. الشركة النشطة هي التي يمكنك إدارة بياناتها.
                        </p>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">3. إدارة البيانات</h4>
                        <p class="text-gray-600 text-sm">
                            بعد تفعيل شركة، يمكنك إضافة عملائها، إنشاء فواتير، وتسجيل مدفوعات.
                        </p>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">4. التبديل بين الشركات</h4>
                        <p class="text-gray-600 text-sm">
                            استخدم زر "تفعيل الشركة" للتبديل بين شركاتك في أي وقت.
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t flex justify-end">
                <button onclick="closeTutorial()" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-check ml-2"></i>
                    فهمت، دعني أجرب
                </button>
            </div>
        </div>
    </div>
</div>
