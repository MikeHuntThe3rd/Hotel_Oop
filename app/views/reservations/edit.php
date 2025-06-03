<?php
$html = <<<HTML
    <form method='post' action='/reservations'>
        <input type='hidden' name='_method' value='PATCH'>
        <input type="hidden" name="id" value="{$reservation->id}">
        <fieldset>
            <label for="reservations">Szoba</label>
            <select name="room_id" id="room_id">
                {$reservation->getRooms($reservation->room_id)}
            </select><br>
            <label for="reservations">Vendég</label>
            <select name="guest_id" id="guest_id">
                {$reservation->getGuests($reservation->guest_id)}
            </select><br>
            <label for="reservations">Napok</label>
            <input type="text" name="days" id="days" value="{$reservation->days}"><br>
            <label for="reservations">Dátum</label>
            <input type="date" name="date" id="date" value="{$reservation->date}"><br>
            <hr>
            <button type="submit" name="btn-update"><i class="fa fa-save">
                </i>&nbsp;Mentés
            </button>
            <a href="/reservations"><i class="fa fa-cancel"></i>&nbsp;Mégse
            </a>
        </fieldset>
    </form>
HTML;

echo $html;