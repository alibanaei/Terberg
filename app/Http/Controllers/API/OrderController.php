<?php

namespace App\Http\Controllers\API;

use App\Services\RepositoryService\Interfaces\RepositoryService;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    private RepositoryService $repositoryService;

    public function __construct(RepositoryService $repositoryService)
    {
        $this->repositoryService = $repositoryService;
    }

    public function index(Request $request): JsonResponse
    {
        [$orders, $links] = $this->repositoryService->index($request->all());

        $orders = new OrderCollection($orders);

        return response()->json(compact('orders', 'links'));
    }

    public function show(string $id): JsonResponse
    {
        $order = $this->repositoryService->show($id);

        return response()->json(compact('order'));
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $data = $request->all();
        $order = $this->repositoryService->store($data);
        $order = new OrderResource($order);
        return response()->json(compact('order'));
    }
}
