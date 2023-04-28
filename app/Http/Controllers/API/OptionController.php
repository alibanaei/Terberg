<?php

namespace App\Http\Controllers\API;

use App\Actions\FactoryActions\ResourceFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreOptionRequest;
use App\Http\Resources\OptionCollection;
use App\Http\Resources\OptionResource;
use App\Http\Resources\ProductCollection;
use App\Http\Responses\APIResponse;
use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page') ?? 10;

        $items = Option::active()->paginate($perPage);

        $options = $items->items();

        $links = [
            'page' => $items->currentpage(),
            'total' => $items->total(),
            'perPage' => $items->perPage(),
        ];

        $options = new OptionCollection($options);

        return APIResponse::makeSuccess(compact('options', 'links'));
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
    public function store(StoreOptionRequest $request, ResourceFactory $resourceFactory)
    {
        $data = $request->all();
        $option = $resourceFactory->createResource($data);
        $optionResource = new OptionResource($option);
        return APIResponse::makeSuccess($optionResource);
    }

    /**
     * Display the specified resource.
     */
    public function show(Option $option)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Option $option)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreOptionRequest $request, Option $option)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Option $option)
    {
        //
    }
}
