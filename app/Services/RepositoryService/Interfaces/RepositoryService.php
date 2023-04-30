<?php

namespace App\Services\RepositoryService\Interfaces;

interface RepositoryService
{
    public function index(array $data);


    public function show(string $id);


    public function store(array $data);


    public function update(array $data, string $id);


    public function destroy(string $id);
}
