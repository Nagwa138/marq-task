<?php

namespace App\Architecture\Repositories\Classes;

use App\Architecture\Repositories\AbstractRepository;
use App\Architecture\Repositories\Interfaces\IInvoiceRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class InvoiceRepository extends AbstractRepository implements IInvoiceRepository
{
    /**
     * @param array $conditions
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function listBy(array $conditions = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->prepareQuery()->where($conditions)->with('user')->paginate($perPage);
    }
}
