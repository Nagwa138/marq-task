<?php

namespace App\Architecture\Repositories\Interfaces;

use App\Models\Invoice;

interface IInvoiceRepository
{
    public function findWithRelations(int $id);

    public function getInvoiceForPrint(int $id);

    public function markAsSent(int $id): bool;

    public function markAsPaid(int $id): bool;

    public function duplicate(int $id): ?Invoice;

    public function getRelatedInvoices(int $customerId, int $excludeId = null);

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
