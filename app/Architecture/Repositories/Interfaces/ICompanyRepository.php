<?php

namespace App\Architecture\Repositories\Interfaces;

use App\Models\Company;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ICompanyRepository
{ public function all(array $filters = []): Collection;
    public function paginate(array $conditions = [], int $perPage = 20): LengthAwarePaginator;
    public function find(int $id);
    public function create(array $data): \Illuminate\Database\Eloquent\Model;
    public function update(array $conditions = [], array $data = []);
    public function delete(int $id): void;
    public function countActive(): int;
    public function withStats(int $id);
}
