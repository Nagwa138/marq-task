<?php

namespace App\Architecture\Services\Interfaces;

use App\Models\Invoice;

interface IInvoiceService
{
    public function getIndexData(array $filters = []): array;
    public function getCreateData(): array;
    public function create(array $data): Invoice;
    public function getDashboardStats(): array;
    public function paginate(array $filters = []);
}
