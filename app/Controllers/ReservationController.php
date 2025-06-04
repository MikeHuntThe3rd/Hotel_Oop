<?php
namespace App\Controllers;
use App\Models\GuestModel;
use App\Models\Model;
use App\Models\ReservationModel;
use App\Models\RoomModel;
use App\Views\Display;

class ReservationController extends Controller
{

    public function __construct()
    {
        $reservation = new ReservationModel();
        parent::__construct($reservation);
    }

    public function index(): void
    {
        $reservations = $this->model->all([
            'order_by' => ['date'],
            'direction' => ['ASC']
        ]);
        $this->render('reservations/index', ['reservations' => $reservations]);
    }

    public function create(): void
    {
        $this->render('reservations/create');
    }
    public function edit(int $id): void
    {
        $reservation = $this->model->find($id);
        if (!$reservation) {
            // Handle invalid ID gracefully
            $_SESSION['warning_message'] = "A foglalás a megadott azonosítóval: $id nem található.";
            $this->redirect('/reservations');
        }
        $this->render('reservations/edit', ['reservation' => $reservation]);
    }

    public function save(array $data): void
    {
        $table_data = $this->model->all();
        foreach($table_data as $row){
            for($i = 0; $i <= $row->days; $i++){
                if(date('Y-m-d', strtotime("+$i days", strtotime($row->date))) == $data['date'] && $row->room_id != $_POST['room_id']){
                    $_SESSION['warning_message'] = "ez a nap már le van foglalva";
                    $this->redirect('/reservations');
                }
            }
            for($i = 0; $i <= $data['days']; $i++){
                if(date('Y-m-d', strtotime("+$i days", strtotime($data['date']))) == $row->date && $row->room_id != $_POST['room_id']){
                    $_SESSION['warning_message'] = "ez a nap már le van foglalva";
                    $this->redirect('/reservations');
                }
            }
            if($row->room_id == $_POST['room_id'] && $row->guest_id == $_POST['guest_id'] && $row->days == $data['days'] && $row->date == $data['date']){
                $_SESSION['warning_message'] = "nem lehet duplikáns";
                $this->redirect('/reservations');
            }
        }
        foreach($data as $curr){
            if (empty($curr) && $curr != 0) {
                $_SESSION['warning_message'] = "hiányos adatbevitel";
                $this->redirect('/reservations');
            }
        }
        if(!is_numeric($data['days'])){
                $_SESSION['error_message'] = "hibás adatbevitel";
                $this->redirect('/reservations');
        }
        $this->model->room_id = $_POST['room_id'];
        $this->model->guest_id = $_POST['guest_id'];
        $this->model->days = $data['days'];
        $this->model->date = $data['date'];
        $this->model->create();
        $this->redirect('/reservations');
    }

    public function update(int $id, array $data): void
    {
        $table_data = $this->model->all();
        foreach($table_data as $row){
            for($i = 0; $i <= $row->days; $i++){
                if(date('Y-m-d', strtotime("+$i days", strtotime($row->date))) == $data['date'] && $row->room_id != $_POST['room_id'] && $row->id != $id){
                    $_SESSION['warning_message'] = "ez a nap már le van foglalva";
                    $this->redirect('/reservations');
                }
            }
            for($i = 0; $i <= $data['days']; $i++){
                if(date('Y-m-d', strtotime("+$i days", strtotime($data['date']))) == $row->date && $row->room_id != $_POST['room_id'] && $row->id != $id){
                    $_SESSION['warning_message'] = "ez a nap már le van foglalva";
                    $this->redirect('/reservations');
                }
            }
            if($row->id != $id && $row->room_id == $_POST['room_id'] && $row->guest_id == $_POST['guest_id'] && $row->days == $data['days'] && $row->date == $data['date']){
                $_SESSION['warning_message'] = "nem lehet duplikáns";
                $this->redirect('/reservations');
            }
        }
        $reservation = $this->model->find($id);
        foreach($data as $curr){
            if (empty($curr)) {
                $_SESSION['warning_message'] = "hiányos adatbevitel";
                $this->redirect('/reservations');
                return;
            }
        }
        if(!is_numeric($data['days'])){
                $_SESSION['error_message'] = "hibás adatbevitel";
                $this->redirect('/reservations');
        }
        $reservation->room_id = $_POST['room_id'];
        $reservation->guest_id = $_POST['guest_id'];
        $reservation->days = $data['days'];
        $reservation->date = $data['date'];
        $reservation->update();
        $this->redirect('/reservations');
    }

    function show(int $id): void
    {
        $reservation = $this->model->find($id);
        if (!$reservation) {
            $_SESSION['warning_message'] = "A foglalás a megadott azonosítóval: $id nem található.";
            $this->redirect('/reservations'); // Handle invalid ID
        }
        $this->render('reservations/show', ['reservation' => $reservation]);
    }

    function delete(int $id): void
    {
        $reservation = $this->model->find($id);
        if ($reservation) {
            $result = $reservation->delete();
            if ($result) {
                $_SESSION['success_message'] = 'Sikeresen törölve';
            }
        }

        $this->redirect('/reservations'); // Redirect regardless of success
    }

}
