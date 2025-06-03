<?php
$html = <<<HTML
    <form method='post' action='/rooms'>
        <input type='hidden' name='_method' value='PATCH'>
        <input type="hidden" name="id" value="{$room->id}">
        <fieldset>
            <label for="rooms">Emelet</label>
            <input type="text" name="floor" id="floor" value="{$room->floor}"><br>
            <label for="rooms">Szobaszám</label>
            <input type="text" name="room_number" id="room_number" value="{$room->room_number}"><br>
            <label for="rooms">Férőhelyek</label>
            <input type="text" name="accommodation" id="accommodation" value="{$room->accommodation}"><br>
            <label for="rooms">Ár</label>
            <input type="text" name="price" id="price" value="{$room->price}"><label for="price"> Ft</label><br>
            <label for="rooms">Megjegyzés</label>
            <input type="text" name="comment" id="comment" value="{$room->comment}">
            <hr>
            <button type="submit" name="btn-update"><i class="fa fa-save">
                </i>&nbsp;Mentés
            </button>
            <a href="/rooms"><i class="fa fa-cancel"></i>&nbsp;Mégse
            </a>
        </fieldset>
    </form>
HTML;

echo $html;