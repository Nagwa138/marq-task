<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    /**
     * عرض الصفحة الرئيسية
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * تسجيل شركة جديدة
     */
    public function registerCompany(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:50',
            'domain' => 'required|string|unique:tenants,domain',
        ]);

        // بدء transaction
        DB::beginTransaction();

        try {
            // 1. إنشاء Tenant (المستأجر)

            $tenant = Tenant::create([
                'name' => $validated['company_name'],
                'domain' => $validated['domain'],
                'database' => 'tenant_' . strtolower(str_replace([' ', '-'], '_', $validated['domain'])),
                'is_active' => true,
            ]);

            // 2. إنشاء المستخدم الأول
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'tenant_id' => $tenant->id,
                'role' => 'admin',
//                'phone' => $validated['phone'],
            ]);

            DB::commit();

            // تسجيل الدخول تلقائياً
            auth()->login($user);

            return redirect()->route('dashboard')
                ->with('success', 'تم إنشاء حساب شركتك بنجاح! مرحباً بك في النظام المحاسبي.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء الحساب. الرجاء المحاولة مرة أخرى.');
        }
    }
}
