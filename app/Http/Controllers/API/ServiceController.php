<?php

namespace App\Http\Controllers\API;

use App\Services\FactoryService\Interfaces\ResourceFactoryService;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreServiceRequest;
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


    public function store(StoreServiceRequest $request, ResourceFactoryService $resourceFactory)
    {
        //
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
