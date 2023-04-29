<?php

namespace App\Http\Controllers\API;

use App\Actions\FactoryActions\ResourceFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\JsonResponse;

class OrderController extends Controller
{

    private ResourceFactory $resourceFactory;

    public function __construct(ResourceFactory $resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page') ?? 10;

        $items = Order::where('user_id', Auth::id())->paginate($perPage);

        $orders = $items->items();

        $links = [
            'page' => $items->currentpage(),
            'total' => $items->total(),
            'perPage' => $items->perPage(),
        ];

        $orders = new OrderCollection($orders);

        return response()->json(compact('orders', 'links'));
    }

    public function show(string $id): JsonResponse
    {
        $order = Order::findOrFail($id);

        if($order->user_id != Auth::id()) {
            abort(403);
        }

        return response()->json(compact('order'));
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $data = $request->all();
        $order = $this->resourceFactory->createResource($data);
        $order = new OrderResource($order);
        return response()->json(compact('order'));
    }
}
