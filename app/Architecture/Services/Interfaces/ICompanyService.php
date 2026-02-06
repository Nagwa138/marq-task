<?php

namespace App\Architecture\Services\Interfaces;

use Illuminate\Http\JsonResponse;

interface ICompanyService
{
    public function all(array $filters = []);
    public function paginate(array $filters = []);
    public function show(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id): bool;

    public function getStats(): array;

    public function deleteLogo(int $id): bool;
    public function getIndexData(array $filters = []): array;
    public function getDashboardStats(): array;
}
