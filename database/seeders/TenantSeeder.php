<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run()
    {
        Tenant::create([
            'name' => 'شركة مثال',
            'domain' => 'demo.localhost',
            'database' => 'tenant_demo',
            'settings' => ['currency' => 'SAR', 'tax_rate' => 15],
            'is_active' => true,
        ]);
    }
}
