<?php

namespace App\Services\RepositoryService\AbstractClasses;

use App\Services\FactoryService\Interfaces\ResourceFactoryService;
use App\Services\RepositoryService\Interfaces\RepositoryService;

abstract class AbstractRepositoryService implements RepositoryService
{
    protected ResourceFactoryService $resourceFactoryService;

    public function __construct(ResourceFactoryService $resourceFactoryService)
    {
        $this->resourceFactoryService = $resourceFactoryService;
    }

    public function index(array $data): array
    {
        $items = $this->retrieveItems($data);

        $itemData = $items->items();

        $links = [
            'page' => $items->currentpage(),
            'total' => $items->total(),
            'perPage' => $items->perPage(),
        ];

        return [$itemData, $links];
    }

    abstract function retrieveItems(array $data);
}
