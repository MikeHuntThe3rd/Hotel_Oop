<?php

namespace App\Models;

class ReservationModel extends Model
{
    public int|null $room_id = null;
    public int|null $guest_id = null;
    public int|null $days = null;
    public string|null $date = null;

    protected static $table = 'reservations';

    public function __construct(?int $room_id = null, ?int $guest_id = null, ?int $days = null, ?string $date = null)
    {
        parent::__construct();

        if ($room_id) {
            $this->room_id = $room_id;
        }

        if ($guest_id) {
            $this->guest_id = $guest_id;
        }

        if ($days) {
            $this->days = $days;
        }

        if ($date) {
            $this->date = $date;
        }
    }

    public function getGuest(){
        $guest = new GuestModel();
        return $guest->find($this->guest_id);
    }
    
    public function getRoom(){
        $room = new RoomModel();
        return $room->find($this->room_id);
    }

    public function getRooms($id = ""){
        $room = new RoomModel();
        $rooms = $room->all();
        $options = "";
        for ($i = 0; $i < count($rooms); $i++){
            $currRoom = $room->findElementsIDByProperty("room_number", $rooms[$i]->room_number);
            $options .= '<option value="' . $currRoom['id'] . '"';
            if ($rooms[$i]->id == $id){
                $options .= ' selected';
            }
            $options .= '>' . $rooms[$i]->room_number . '</option>';
        }
        return $options;
    }

    public function getGuests($id = ""){
        $guest = new GuestModel();
        $guests = $guest->all();
        $options = "";
        for ($i = 0; $i < count($guests); $i++) {
            $options .= '<option value="' . $guest->findElementsIDByProperty("name", $guests[$i]->name)['id'] . '"';
            if ($guests[$i]->id == $id) {
                $options .= ' selected';
            }
            $options .= '>' . $guests[$i]->name . '</option>';
        }
        return $options;
    }
}
