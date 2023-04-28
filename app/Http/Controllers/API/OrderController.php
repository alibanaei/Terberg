<?php

namespace App\Http\Controllers\API;

use App\Actions\FactoryActions\ResourceFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Responses\APIResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request, ResourceFactory $resourceFactory)
    {
        $data = $request->all();
        $order = $resourceFactory->createResource($data);
        $orderResource = new OrderResource($order);
        return APIResponse::makeSuccess($orderResource);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreOrderRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
