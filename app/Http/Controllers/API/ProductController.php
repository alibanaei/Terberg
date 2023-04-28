<?php

namespace App\Http\Controllers\API;

use App\Actions\FactoryActions\ResourceFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreProductRequest;
use App\Http\Requests\API\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Responses\APIResponse;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
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

        return APIResponse::makeSuccess(compact('products', 'links'));
    }


    public function create()
    {
        //
    }


    public function store(StoreProductRequest $request, ResourceFactory $resourceFactory)
    {
        $data = $request->all();
        $product = $resourceFactory->createResource($data);
        $productResource = new StoreProductRequest($product);
        return APIResponse::makeSuccess($productResource);
    }


    public function show(string $id)
    {
        $product = Product::active()->findOrFail($id);

        return APIResponse::makeSuccess(compact('product'));
    }


    public function edit(string $id)
    {
        //
    }


    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::findOrFail($id);

        $product->update($request->all());

        return APIResponse::makeSuccess(compact('product'));
    }


    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return APIResponse::makeSuccess([]);
    }
}
