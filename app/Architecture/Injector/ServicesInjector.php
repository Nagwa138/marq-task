<?php

namespace App\Architecture\Injector;

use App\Architecture\Services\Classes\CompanyService;
use App\Architecture\Services\Classes\CustomerService;
use App\Architecture\Services\Classes\InvoiceService;
use App\Architecture\Services\Interfaces\ICompanyService;
use App\Architecture\Services\Interfaces\ICustomerService;
use App\Architecture\Services\Interfaces\IInvoiceService;
use Illuminate\Support\ServiceProvider;

class ServicesInjector extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ICompanyService::class, CompanyService::class);
        $this->app->bind(ICustomerService::class, CustomerService::class);
        $this->app->bind(IInvoiceService::class, InvoiceService::class);
    }
}
