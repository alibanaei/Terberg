<?php

namespace App\Services\RepositoryService\Implementations;

use App\Services\RepositoryService\AbstractClasses\AbstractRepositoryService;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use function abort;

class OrderRepository extends AbstractRepositoryService
{

    public function retrieveItems(array $data): LengthAwarePaginator
    {
        $perPage = $data['per_page'] ?? 10;

        return Order::where('user_id', Auth::id())->paginate($perPage);
    }

    public function show(string $id): Order
    {
        $order = Order::findOrFail($id);

        if($order->user_id != Auth::id()) {
            abort(403);
        }

        return $order;
    }

    public function store(array $data): Order
    {
        return $this->resourceFactoryService->createResource($data);
    }

    public function update(array $data, string $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy(string $id)
    {
        // TODO: Implement delete() method.
    }
}
