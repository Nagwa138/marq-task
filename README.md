# 🏢 Smart Accountant - Multi-Company Accounting System

A comprehensive accounting system that manages multiple companies with a tenant-based architecture, featuring complete customer, invoice, and payment management.

## 🚀 Quick Start

### Prerequisites

- PHP 8.1+
- Composer 2.0+
- MySQL 5.7+ or PostgreSQL 10+
- Laravel 10+

### Installation

1. **Clone the project**:
```bash
git clone <repository-url>
cd smart-accounting-system
```

2. **Install dependencies**:
```bash
composer install
```

3. **Setup environment**:
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**:
   Edit `.env` file:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=smart_accounting
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run migrations**:
```bash
php artisan migrate --seed
```

6. **Start development server**:
```bash
php artisan serve
```

Visit: http://localhost:8000

## 👤 Default Login Credentials

After setup, use these credentials:

- **Email:** admin@example.com
- **Password:** password

## 🏗️ System Architecture

### Tenant System (Multi-Company)
- Each tenant represents a separate client of the system
- Each tenant has its own database (optional)
- Complete data isolation between tenants

### Companies
- Each tenant can add multiple companies
- Each company represents a separate accounting entity
- Users can switch between companies within the same tenant

### Core Relationships
```
Tenant (1) → (n) Companies
Company (1) → (n) Customers
Customer (1) → (n) Invoices
Invoice (1) → (n) Payments
```

## 📊 Key Features

### 1. Multi-Company System
- Create and manage multiple companies
- Complete data isolation between companies
- Quick switching between active companies

### 2. Customer Management
- Register customers for each company
- Track customer balances
- Invoice and payment history

### 3. Invoice System
- Create detailed invoices
- Add multiple invoice items
- Automatic tax calculation
- Invoice statuses: draft, sent, paid, overdue

### 4. Payment System
- Record payments
- Track payment status
- Link payments to invoices
- Automatically update customer balances

### 5. Reports & Statistics
- Detailed statistics for each company
- Monthly revenue reports
- Invoice status analysis
- Payment and balance tracking

## 🗄️ Database Structure

### Main Tables

#### tenants
```sql
id              INT (PK)
name            VARCHAR(255)
domain          VARCHAR(255) UNIQUE
database        VARCHAR(255) UNIQUE
settings        JSON
is_active       BOOLEAN DEFAULT true
timestamps
```

#### companies
```sql
id              INT (PK)
tenant_id       INT (FK → tenants)
name            VARCHAR(255)
email           VARCHAR(255) UNIQUE
phone           VARCHAR(20)
address         TEXT
tax_number      VARCHAR(50)
logo            VARCHAR(255)
timestamps
```

#### users
```sql
id              INT (PK)
tenant_id       INT (FK → tenants) NULLABLE
company_id      INT (FK → companies) NULLABLE
name            VARCHAR(255)
email           VARCHAR(255) UNIQUE
password        VARCHAR(255)
role            ENUM('admin', 'accountant', 'viewer')
timestamps
```

#### customers
```sql
id              INT (PK)
tenant_id       INT (FK → tenants)
company_id      INT (FK → companies)
name            VARCHAR(255)
email           VARCHAR(255)
phone           VARCHAR(20)
address         TEXT
tax_number      VARCHAR(50)
balance         DECIMAL(15,2) DEFAULT 0
timestamps
```

#### invoices
```sql
id              INT (PK)
tenant_id       INT (FK → tenants)
company_id      INT (FK → companies)
customer_id     INT (FK → customers)
invoice_number  VARCHAR(255) UNIQUE
issue_date      DATE
due_date        DATE
subtotal        DECIMAL(15,2)
tax             DECIMAL(15,2)
total           DECIMAL(15,2)
status          ENUM('draft', 'sent', 'paid', 'overdue')
notes           TEXT
timestamps
```

#### invoice_items
```sql
id              INT (PK)
invoice_id      INT (FK → invoices)
description     VARCHAR(255)
quantity        DECIMAL(10,2)
unit_price      DECIMAL(15,2)
total           DECIMAL(15,2)
timestamps
```

#### payments
```sql
id              INT (PK)
tenant_id       INT (FK → tenants)
invoice_id      INT (FK → invoices)
customer_id     INT (FK → customers)
amount          DECIMAL(15,2)
payment_date    DATE
payment_method  ENUM('cash', 'bank_transfer', 'credit_card', 'check')
reference       VARCHAR(255)
notes           TEXT
timestamps
```

## 🎨 User Interface

### Design Overview
- Fully Arabic interface (RTL support)
- Modern design using Tailwind CSS
- Responsive for all devices
- Unified color scheme (Indigo)

### Main Sections

#### 1. Dashboard
- General statistics
- Active companies
- Recent activities
- Charts and graphs

#### 2. Company Management
- Companies list
- Add new company
- Company details
- Switch between companies

#### 3. Customer Management
- Customer records
- Add new customer
- Customer details
- Customer invoices

#### 4. Invoice Management
- Create new invoices
- Invoices list
- Invoice details
- Download invoices as PDF

#### 5. Payment Management
- Record payments
- Payment history
- Link payments to invoices

## 🔧 Technical Architecture

### Design Patterns Used

#### 1. Repository Pattern
```php
// Each model has its own repository
App\Architecture\Repositories\CustomerRepository
App\Architecture\Repositories\CompanyRepository
App\Architecture\Repositories\InvoiceRepository
```

#### 2. Service Pattern
```php
// Business logic separation
App\Architecture\Services\CustomerService
App\Architecture\Services\CompanyService
App\Architecture\Services\StatisticsService
```

#### 3. Abstract Repository
```php
// Common base for all Repositories
App\Architecture\Repositories\AbstractRepository
```

### Core Structure

```
app/
├── Architecture/
│   ├── Repositories/
│   │   ├── AbstractRepository.php     # Common base
│   │   ├── Interfaces/               # Repository interfaces
│   │   │   ├── ICompanyRepository.php
│   │   │   ├── ICustomerRepository.php
│   │   │   └── IInvoiceRepository.php
│   │   └── Classes/                  # Repository implementations
│   │       ├── CompanyRepository.php
│   │       ├── CustomerRepository.php
│   │       └── InvoiceRepository.php
│   ├── Services/
│   │   ├── Interfaces/               # Service interfaces
│   │   │   ├── ICompanyService.php
│   │   │   ├── ICustomerService.php
│   │   │   └── IStatisticsService.php
│   │   └── Classes/                  # Service implementations
│   │       ├── CompanyService.php
│   │       ├── CustomerService.php
│   │       └── StatisticsService.php
│   └── Responder/                    # Response management
├── Enums/                           # Type-safe enums
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php   # Dashboard
│   │   ├── CompanyController.php     # Companies
│   │   ├── CustomerController.php    # Customers
│   │   ├── InvoiceController.php     # Invoices
│   │   └── PaymentController.php     # Payments
│   ├── Requests/                     # Form requests
│   │   ├── Customer/
│   │   │   ├── StoreCustomerRequest.php
│   │   │   └── UpdateCustomerRequest.php
│   │   └── Company/
│   │       ├── StoreCompanyRequest.php
│   │       └── UpdateCompanyRequest.php
│   └── Resources/                    # API resources
└── Models/                          # Models
    ├── Tenant.php
    ├── Company.php
    ├── Customer.php
    ├── Invoice.php
    ├── InvoiceItem.php
    └── Payment.php
```

## 🚦 How to Use

### 1. User Login
```php
// User login with email and password
// Store tenant_id in session
session(['tenant_id' => auth()->user()->tenant_id]);
```

### 2. Add New Company
```php
// Add company linked to tenant
$company = Company::create([
    'tenant_id' => auth()->user()->tenant_id,
    'name' => 'Company Name',
    'email' => 'company@example.com',
    // ... other data
]);
```

### 3. Switch Between Companies
```php
// Store active company in session
session(['active_company_id' => $companyId]);

// Fetch data based on active company
$customers = Customer::where('company_id', session('active_company_id'))->get();
```

### 4. Create Invoice
```php
// Create new invoice
$invoice = Invoice::create([
    'tenant_id' => auth()->user()->tenant_id,
    'company_id' => session('active_company_id'),
    'customer_id' => $customerId,
    'invoice_number' => 'INV-2024-001',
    // ... invoice data
]);

// Add invoice items
$invoice->items()->create([
    'description' => 'Item description',
    'quantity' => 1,
    'unit_price' => 100,
    'total' => 100
]);

// Update customer balance
$customer->increment('balance', $invoice->total);
```

## ⚙️ Configuration

### Environment File (.env)
```env
APP_NAME="Smart Accountant"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_accounting
DB_USERNAME=root
DB_PASSWORD=

TENANCY_DB_SEPARATE=false  # Separate database for each tenant
```

### Tenancy Configuration (config/tenancy.php)
```php
return [
    'enabled' => env('TENANCY_ENABLED', false),
    
    'tenant_model' => \App\Models\Tenant::class,
    
    'domains' => [
        'central' => env('CENTRAL_DOMAIN', 'localhost'),
    ],
    
    'database' => [
        'separate_databases' => env('TENANCY_DB_SEPARATE', false),
        'prefix' => 'tenant_',
    ],
];
```

## 🔐 Permission System

### User Roles
1. **System Administrator (Admin)**
    - Manage all companies
    - Create new users
    - Full permissions

2. **Accountant**
    - Manage assigned companies
    - Create invoices and payments
    - Manage customers

3. **Viewer**
    - View reports only
    - No edit permissions

### Authorization Policies
```php
// Company policy
public function view(User $user, Company $company)
{
    return $user->tenant_id === $company->tenant_id;
}

// Customer policy
public function update(User $user, Customer $customer)
{
    return $user->tenant_id === $customer->tenant_id 
        && $user->company_id === $customer->company_id;
}
```

## 📈 Statistics & Reports

### General Statistics
- Active companies count
- Total customers
- Total invoices
- Monthly revenue
- Overdue invoices

### Available Reports
1. **Monthly Sales Report**
2. **Customer Balances Report**
3. **Invoice Status Report**
4. **Payments Report**

## 🔄 API Endpoints

### Companies
```
GET    /api/companies          # List companies
POST   /api/companies          # Create company
GET    /api/companies/{id}     # Company details
PUT    /api/companies/{id}     # Update company
DELETE /api/companies/{id}     # Delete company
POST   /api/company/switch     # Switch active company
```

### Customers
```
GET    /api/customers          # List customers
POST   /api/customers          # Create customer
GET    /api/customers/{id}     # Customer details
PUT    /api/customers/{id}     # Update customer
DELETE /api/customers/{id}     # Delete customer
GET    /api/customers/search   # Search customers
```

### Invoices
```
GET    /api/invoices           # List invoices
POST   /api/invoices           # Create invoice
GET    /api/invoices/{id}      # Invoice details
PUT    /api/invoices/{id}      # Update invoice
DELETE /api/invoices/{id}      # Delete invoice
POST   /api/invoices/{id}/send # Send invoice
GET    /api/invoices/{id}/pdf  # Download PDF
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Company tests
php artisan test --filter CompanyTest

# Customer tests
php artisan test --filter CustomerTest

# Invoice tests
php artisan test --filter InvoiceTest

# With coverage
php artisan test --coverage
```

## 🚀 Deployment

### Basic Setup
```bash
# Setup project
composer install --optimize-autoloader --no-dev

# Setup storage
php artisan storage:link

# Build assets
npm run build

# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database Setup
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE smart_accounting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
php artisan migrate --force

# Seed data (optional)
php artisan db:seed --force
```

## 🐛 Troubleshooting

### Common Issues

1. **Tenant Error**
```bash
# Check tenant_id in session
dd(session('tenant_id'));

# Check user tenant association
dd(auth()->user()->tenant_id);
```

2. **Active Company Error**
```bash
# Activate company
session(['active_company_id' => $companyId]);

# Verify active company
dd(session('active_company_id'));
```

3. **Relationship Errors**
```bash
# Verify model relationships
$customer->load('company', 'invoices', 'payments');

# Check queries
\DB::enableQueryLog();
// Your queries
dd(\DB::getQueryLog());
```

### Debug Mode
```env
APP_DEBUG=true
APP_ENV=local
```

View logs:
```bash
tail -f storage/logs/laravel.log
```

## 📱 Future Development

### Planned Features
1. **Inventory System**
2. **Bank Integration**
3. **Advanced Reports**
4. **Full API**
5. **Mobile App**
6. **Budgeting System**
7. **Financial Analysis**

### Potential Integrations
1. **Payment Gateways** (PayPal, Stripe)
2. **Accounting Systems** (QuickBooks, Zoho Books)
3. **ERP Systems** (Odoo, ERPNext)
4. **E-commerce Platforms**

## 📄 License

This project is for assessment and educational purposes.

## 📞 Support & Contact

For questions and support:
- **Developer:** Nagwa Ali
- **Email:** nnnnali123@gmail.com
- **Created:** 2026

---

**Happy to serve you!** 🌟
