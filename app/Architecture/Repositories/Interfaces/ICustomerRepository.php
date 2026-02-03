<?php

namespace App\Architecture\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ICustomerRepository
{
    /**
     * Get all customers with pagination
     */
    public function paginate(array $conditions = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get customers with statistics
     */
//    public function paginateWithStats(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find customer by ID
     */
    public function find(int $id);

    /**
     * Find customer with relations
     */
//    public function findWithRelations(int $id, array $relations = []);

    /**
     * Create new customer
     */
    public function create(array $data);
//
//    /**
//     * Update customer
//     */
//    public function update(int $id, array $data);
//
//    /**
//     * Delete customer
//     */
//    public function delete(int $id): bool;
//
//    /**
//     * Get query builder
//     */
//    public function query();
//
//    /**
//     * Get customers by company
//     */
//    public function getByCompany(int $companyId, array $filters = []): Collection;
//
//    /**
//     * Get customers with overdue invoices
//     */
//    public function getWithOverdueInvoices(array $filters = []): Collection;
//
//    /**
//     * Search customers
//     */
//    public function search(string $query, int $limit = 10): Collection;
//
//    /**
//     * Get customers with positive balance
//     */
//    public function getWithPositiveBalance(array $filters = []): Collection;
//
//    /**
//     * Get customers with negative balance
//     */
//    public function getWithNegativeBalance(array $filters = []): Collection;
//
//    /**
//     * Get recent customers
//     */
//    public function getRecent(int $limit = 10): Collection;
//
//    /**
//     * Get customer statistics
//     */
//    public function getStats(): array;
//
//    /**
//     * Update customer balance
//     */
//    public function updateBalance(int $customerId): float;
//
//    /**
//     * Get top customers by balance
//     */
//    public function getTopCustomersByBalance(int $limit = 10): Collection;
}
