<?php

namespace App\Architecture\Services\Classes;

use App\Architecture\Repositories\Interfaces\ICompanyRepository;
use App\Architecture\Services\Interfaces\ICompanyService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CompanyService implements ICompanyService
{
    public function __construct(
        private readonly ICompanyRepository $companyRepository
    ) {}

    public function all(array $filters = [])
    {
        return $this->companyRepository->all($filters);
    }

    public function paginate(array $filters = [])
    {
        return $this->companyRepository->paginate($filters);
    }

    public function show(int $id)
    {
        return $this->companyRepository->withStats($id);
    }

    public function create(array $data)
    {
        if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
            $data['logo'] = $this->uploadLogo($data['logo']);
        }

        return $this->companyRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
            $data['logo'] = $this->uploadLogo($data['logo']);
        }

        return $this->companyRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->companyRepository->delete($id);
    }

    public function getStats(): array
    {
        return [
            'total' => $this->companyRepository->countActive(),
            'recent' => $this->companyRepository->paginate([], 5),
        ];
    }

    protected function uploadLogo(UploadedFile $file): string
    {
        $path = $file->store('companies/logos', 'public');

        return str_replace('public/', '', $path);
    }

    public function deleteLogo(int $id): bool
    {
        $company = $this->companyRepository->first(['id' => $id]);

        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
            $company->update(['logo' => null]);

            return true;
        }

        return false;
    }
}
