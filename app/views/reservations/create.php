<?php

use App\Models\ReservationModel;
$reservation = new ReservationModel();

echo <<<HTML
        <form method='post' action='/reservations'>
            <fieldset>
                <label for="reservations">Szoba</label>
                <select name="room_id" id="room_id">
                    {$reservation->getRooms()}
                </select><br>
                <label for="reservations">Vendég</label>
                <select name="guest_id" id="guest_id">
                    {$reservation->getGuests()}
                </select><br>
                <label for="reservations">Napok</label>
                <input type="text" name="days" id="days">
                <label for="reservations">Dátum</label>
                <input type="date" name="date" id="date"><br>
                <hr>
                <button type="submit" name="btn-save">
                    <i class="fa fa-save"></i>&nbsp;Mentés
                </button>
                <a href="/reservations"><i class="fa fa-cancel">                    
                    </i>&nbsp;Mégse
                </a>
            </fieldset>
        </form>
    HTML;