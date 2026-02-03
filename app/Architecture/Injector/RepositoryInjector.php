<?php

namespace App\Architecture\Injector;

use App\Architecture\Repositories\Classes\CompanyRepository;
use App\Architecture\Repositories\Classes\CustomerRepository;
use App\Architecture\Repositories\Classes\InvoiceRepository;
use App\Architecture\Repositories\Interfaces\ICompanyRepository;
use App\Architecture\Repositories\Interfaces\ICustomerRepository;
use App\Architecture\Repositories\Interfaces\IInvoiceRepository;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Support\ServiceProvider;

class RepositoryInjector extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ICompanyRepository::class, function ($app) {
            return new CompanyRepository($app->make(Company::class));
        });
        $this->app->singleton(ICustomerRepository::class, function ($app) {
            return new CustomerRepository($app->make(Customer::class));
        });
        $this->app->singleton(IInvoiceRepository::class, function ($app) {
            return new InvoiceRepository($app->make(Invoice::class));
        });
    }
}
