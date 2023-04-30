<?php

namespace App\Services\RepositoryService\Implementations;

use App\Services\RepositoryService\AbstractClasses\AbstractRepositoryService;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use function abort;

class ProductRepository extends AbstractRepositoryService
{

    function retrieveItems(array $data): LengthAwarePaginator
    {
        $perPage = $data['per_page'] ?? 10;

        return Product::active()->paginate($perPage);
    }

    public function show(string $id): Product
    {
        return Product::active()->findOrFail($id);
    }

    public function store(array $data): Product
    {
        return $this->resourceFactoryService->createResource($data);
    }

    public function update(array $data, string $id): Product
    {
        $product = Product::findOrFail($id);

        $product->update($data);

        return $product;
    }

    public function destroy(string $id): void
    {
        $product = Product::findOrFail($id);

        try {
            $product->delete();
        } catch (\Exception $exception) {
            abort(500);
        }

    }
}
