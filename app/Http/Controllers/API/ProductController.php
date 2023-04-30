<?php

namespace App\Http\Controllers\API;

use App\Services\RepositoryService\Interfaces\RepositoryService;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreProductRequest;
use App\Http\Requests\API\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private RepositoryService $repositoryService;

    public function __construct(RepositoryService $repositoryService)
    {
        $this->repositoryService = $repositoryService;
    }

    public function index(Request $request): JsonResponse
    {
        [$products, $links] = $this->repositoryService->index($request->all());

        $products = new ProductCollection($products);

        return response()->json(compact('products', 'links'));
    }

    public function show(string $id): JsonResponse
    {
        $product = $this->repositoryService->show($id);

        return response()->json(compact('product'));
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $data = $request->all();

        $product = $this->repositoryService->store($data);

        $product = new ProductResource($product);

        return response()->json(compact('product'));
    }


    public function update(UpdateProductRequest $request, string $id): JsonResponse
    {
        $data = $request->all();

        $product = $this->repositoryService->update($data, $id);

        $product = new ProductResource($product);

        return response()->json(compact('product'));
    }


    public function destroy(string $id): JsonResponse
    {
        $this->repositoryService->destroy($id);

        return response()->json();
    }
}
