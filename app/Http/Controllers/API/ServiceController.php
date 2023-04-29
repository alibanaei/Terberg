<?php

namespace App\Http\Controllers\API;

use App\Actions\FactoryActions\ResourceFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreServiceRequest;
use App\Http\Resources\ServiceCollection;
use App\Http\Resources\ServiceResource;
use App\Http\Responses\APIResponse;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{

    public function index(Request $request)
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(StoreServiceRequest $request, ResourceFactory $resourceFactory)
    {
        $data = $request->all();
        $service = $resourceFactory->createResource($data);
        $serviceResource = new ServiceResource($service);
        return APIResponse::makeSuccess($serviceResource);
    }


    public function show(Service $service)
    {
        //
    }


    public function edit(Service $service)
    {
        //
    }


    public function update(StoreServiceRequest $request, Service $service)
    {
        //
    }


    public function destroy(Service $service)
    {
        //
    }
}
