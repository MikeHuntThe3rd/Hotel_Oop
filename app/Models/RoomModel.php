<?php

namespace App\Models;

class RoomModel extends Model
{
    public int|null $floor = null;

    public int|null $room_number = null;
    public int|null $accommodation = null;
    public int|null $price = null;
    public string|null $comment = null;

    protected static $table = 'rooms';

    public function __construct(?int $floor = null, ?int $room_number = null, ?int $accommodation = null, ?int $price = null, ?string $comment = null)
    {
        parent::__construct();
        
        if ($floor) {
            $this->floor = $floor;
        }

        if ($room_number) {
            $this->room_number = $room_number;
        }

        if ($accommodation) {
            $this->accommodation = $accommodation;
        }

        if ($price) {
            $this->price = $price;
        }

        if ($comment) {
            $this->comment = $comment;
        }
    }
}