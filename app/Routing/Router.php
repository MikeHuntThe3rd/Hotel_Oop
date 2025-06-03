<?php
namespace App\Routing;

use App\Controllers\HomeController;
use App\Controllers\ReservationController;
use App\Controllers\GuestController;
use App\Controllers\RoomController;
use App\Views\Display;

class Router {

    public function handle(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $requestUri = $_SERVER['REQUEST_URI'];

        // Check if the method is overridden by the `_method` field
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        // Dispatch the request
        $this->dispatch($method, $requestUri);
    }

    private function dispatch(string $method, string $requestUri): void
    {
        switch ($method) {
            case 'GET':
                $this->handleGetRequests($requestUri);
                break;
            case 'POST':
                $this->handlePostRequests($requestUri);
                break;
            case 'PATCH':
                $this->handlePatchRequests($requestUri);
                break;
            case 'DELETE':
                $this->handleDeleteRequests($requestUri);
                break;
            default:
                $this->methodNotAllowed();
        }
    }

    private function handleGetRequests(mixed $requestUri)
    {
        switch ($requestUri) {
            case '/':
                HomeController::index();
                return;
            case '/reservations':
                $reservationController = new ReservationController();
                $reservationController->index();
                break;
            case '/guests':
                $guestController = new GuestController();
                $guestController->index();
                break;
            case '/rooms':
                $roomController = new RoomController();
                $roomController->index();
                break;   
            default:
                $this->notFound();

        }
    }

    private function handlePostRequests(mixed $requestUri)
    {
        $data = $this->filterPostData($_POST);
        $id = $data['id'] ?? null;

        switch ($requestUri) {
            case '/reservations':
                if (!empty($data)) {
                    $reservationController = new reservationController();
                    $reservationController->save($data);
                }
                break;
            case '/reservations/create':
                $reservationController =  new reservationController();
                $reservationController->create();
                break;
            case '/reservations/edit':
                $reservationController =  new ReservationController();
                $reservationController->edit($id);
                break;
            case '/guests':
                if (!empty($data)) {
                    $guestController = new GuestController();
                    $guestController->save($data);
                }
                break;
            case '/guests/create':
                $guestController = new GuestController();
                $guestController->create();
                break;
            case '/guests/edit':
                $guestController = new GuestController();
                $guestController->edit($id);
                break;
            case '/rooms':
                if (!empty($data)) {
                    $roomController = new RoomController();
                    $roomController->save($data);
                }
                break;
            case '/rooms/create':
                $roomController = new RoomController();
                $roomController->create();
                break;
            case '/rooms/edit':
                $roomController = new RoomController();
                $roomController->edit($id);
                break;
            default:
                $this->notFound();
        }
    }

    private function handlePatchRequests(mixed $requestUri) {
        $data = $this->filterPostData($_POST);
        switch ($requestUri) {
            case '/reservations':
                $id = $data['id'] ?? null;
                $reservationController =  new reservationController();
                $reservationController->update($id, $data);
                break;
            case '/guests':
                $id = $data['id'] ?? null;
                $guestController = new GuestController();
                $guestController->update($id, $data);
                break;
            case '/rooms':
                $id = $data['id'] ?? null;
                $roomController = new RoomController();
                $roomController->update($id, $data);
                break;
            default:
                $this->notFound();
        }
    }

    private function handleDeleteRequests(mixed $requestUri) {
        $data = $this->filterPostData($_POST);

        switch ($requestUri) {
            case '/reservations':
                $reservationController = new reservationController();
                $reservationController->delete((int) $data['id']);
                break;
            case '/guests':
                $guestController = new GuestController();
                $guestController->delete((int) $data['id']);
                break;
            case '/rooms':
                $roomController = new RoomController();
                $roomController->delete((int) $data['id']);
                break;
            default:
                $this->notFound();
        }
    }

    private function filterPostData(array $data): array
    {
        // Remove unnecessary keys in a clean and simple way
        $filterKeys = ['_method', 'submit', 'btn-del', 'btn-save', 'btn-edit', 'btn-plus', 'btn-update'];
        return array_diff_key($data, array_flip($filterKeys));
    }

    private function notFound(): void
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        Display::message("404 Not Found", 'error');
    }

    private function methodNotAllowed(): void
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        Display::message("405 Method Not Allowed", 'error');
    }

}
