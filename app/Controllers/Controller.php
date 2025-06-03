<?php

namespace App\Controllers;

use App\Interfaces\ControllerInterface;
use App\Models\Model;
use App\Views\View;

abstract class Controller implements ControllerInterface
{
    protected Model $model;
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    abstract function index();
    abstract function show(int $id);
    abstract function create();
    abstract function save(array $data);
    abstract function edit(int $id);
    abstract function update(int $id, array $data);
    abstract function delete(int $id);

    // Helper method to encapsulate rendering logic
    protected function render(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    // Helper method to handle redirects consistently
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}