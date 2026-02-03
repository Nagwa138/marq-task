<?php

namespace App\Architecture\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ICustomerService
{
    /**
     * Get all customers with filters
     */
    public function all(array $filters = []): LengthAwarePaginator;

    /**
     * Get customer by ID with details
     */
    public function show(int $id);

    /**
     * Create new customer
     */
    public function create(array $data);

    /**
     * Update existing customer
     */
    public function update(int $id, array $data);

    /**
     * Delete customer
     */
    public function delete(int $id): bool;

    /**
     * Get customer statistics
     */
    public function getStats(): array;

    /**
     * Get customer invoices
     */
    public function getInvoices(int $customerId, int $limit = null): Collection;

    /**
     * Get customer payments
     */
    public function getPayments(int $customerId, int $limit = null): Collection;

    /**
     * Update customer balance
     */
    public function updateBalance(int $customerId): float;

    /**
     * Search customers for autocomplete
     */
    public function search(string $query, int $limit = 10): Collection;
}
