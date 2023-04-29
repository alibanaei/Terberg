<?php

namespace App\Http\Controllers\API;

use App\Actions\FactoryActions\ResourceFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreProductRequest;
use App\Http\Requests\API\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ResourceFactory $resourceFactory;

    public function __construct(ResourceFactory $resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page') ?? 10;

        $items = Product::active()->paginate($perPage);

        $products = $items->items();

        $links = [
            'page' => $items->currentpage(),
            'total' => $items->total(),
            'perPage' => $items->perPage(),
        ];

        $products = new ProductCollection($products);

        return response()->json(compact('products', 'links'));
    }

    public function show(string $id): JsonResponse
    {
        $product = Product::active()->findOrFail($id);

        return response()->json(compact('product'));
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $data = $request->all();

        $product = $this->resourceFactory->createResource($data);

        $product = new ProductResource($product);

        return response()->json(compact('product'));
    }


    public function update(UpdateProductRequest $request, string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $product->update($request->all());

        $product = new ProductResource($product);

        return response()->json(compact('product'));
    }


    public function destroy(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json();
    }
}
