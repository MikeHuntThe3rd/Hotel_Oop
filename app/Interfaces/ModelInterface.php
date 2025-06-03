<?php

namespace App\Interfaces;

interface ModelInterface
{
    function find(int $id): ?static;
    function all($orderBy = []): array;
    function delete();
    public function create();
    public function update();
}
