<?php

namespace App\Architecture\Repositories\Interfaces;

use App\Models\Invoice;

interface IInvoiceRepository
{
    public function all(array $filters = []);
    public function paginate(array $conditions = [], int $perPage = 10);
    public function find(int $id);
    public function create(array $data);
    public function update(array $conditions, array $data);
    public function withRelations(int $id);
    public function getStats(): array;
    public function getDashboardStats(): array;
    public static function generateInvoiceNumber(): string;
}
