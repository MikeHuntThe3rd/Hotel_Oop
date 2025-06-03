<?php
namespace App\Controllers;
use App\Models\GuestModel;
use App\Views\Display;
use App\Database\Database;

class GuestController extends Controller {

    public function __construct()
    {
        $guest = new GuestModel();
        parent::__construct($guest);
    }

    public function index(): void
    {
        $guests = $this->model->all(['order_by' => ['name'], 
            'direction' => ['ASC']]);
        $this->render('guests/index', ['guests' => $guests]);
    }

    public function create(): void
    {
        $this->render('guests/create');
    }
    public function edit(int $id): void
    {
        $guest = $this->model->find($id);
        if (!$guest) {
            // Handle invalid ID gracefully
            $_SESSION['warning_message'] = "A vendég a megadott azonosítóval: $id nem található.";
            $this->redirect('/guests');
        }
        $this->render('guests/edit', ['guest' => $guest]);
    }

    public function save(array $data): void
    {
        $table_data = $this->model->all();
        foreach($table_data as $row){
            if($row->name == $data['name'] && $row->age == $data['age']){
                $_SESSION['warning_message'] = "nem lehet duplikáns";
                $this->redirect('/guests');
            }
        }
        foreach($data as $curr){
            if (empty($curr)) {
                $_SESSION['warning_message'] = "hiányos adatbevitel";
                $this->redirect('/guests');
            }
        }
        if(is_numeric($data['name']) || !is_numeric($data['age'])){
                $_SESSION['error_message'] = "hibás adatbevitel";
                $this->redirect('/guests');
        }
        $this->model->name = $data['name'];
        $this->model->age = $data['age'];
        $this->model->create();
        $this->redirect('/guests');
    }

    public function update(int $id, array $data): void
    {
        $guest = $this->model->find($id);
        $table_data = $this->model->all();
        foreach($table_data as $row){
            if($row->id != $id && $row->name == $data['name'] && $row->age == $data['age']){
                $_SESSION['warning_message'] = "nem lehet duplikáns";
                $this->redirect('/guests');
            }
        }
        foreach($data as $curr){
            if (empty($curr)) {
                $_SESSION['warning_message'] = "hiányos adatbevitel";
                $this->redirect('/guests');
                return;
            }
        }
        if(is_numeric($data['name']) || !is_numeric($data['age'])){
                $_SESSION['error_message'] = "hibás adatbevitel";
                $this->redirect('/guests');
        }
        $guest->name = $data['name'];
        $guest->age = $data['age'];
        $guest->update();
        $this->redirect('/guests');
    }

    function show(int $id): void
    {
        $guest = $this->model->find($id);
        if (!$guest) {
            $_SESSION['warning_message'] = "A vendég a megadott azonosítóval: $id nem található.";
            $this->redirect('/guests'); // Handle invalid ID
        }
        $this->render('guests/show', ['guest' => $guest]);
    }

    function delete(int $id): void
    {
        $guest = $this->model->find($id);
        if ($guest) {
            $result = $guest->delete();
            if ($result) {
                $_SESSION['success_message'] = 'Sikeresen törölve';
            }
        }

        $this->redirect('/guests'); // Redirect regardless of success
    }

}
