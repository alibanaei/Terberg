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

    private ResourceFactory $resourceFactory;

    public function __construct(ResourceFactory $resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;
    }

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


    public function create()
    {
        //
    }


    public function store(StoreOptionRequest $request)
    {
        $data = $request->all();
        $option = $this->resourceFactory->createResource($data);
        $optionResource = new OptionResource($option);
        return APIResponse::makeSuccess($optionResource);
    }


    public function show(Option $option)
    {
        //
    }


    public function edit(Option $option)
    {
        //
    }


    public function update(StoreOptionRequest $request, Option $option)
    {
        //
    }


    public function destroy(Option $option)
    {
        //
    }
}
