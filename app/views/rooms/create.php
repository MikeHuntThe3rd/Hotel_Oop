<?php
echo <<<HTML
        <form method='post' action='/rooms'>
            <fieldset>
                <label for="rooms">Emelet</label>
                <input type="text" name="floor" id="floor">
                <label for="rooms">Szobaszám</label>
                <input type="text" name="room_number" id="room_number"><br>
                <label for="rooms">Férőhelyek</label>
                <input type="text" name="accommodation" id="accommodation">
                <label for="rooms">Ár</label>
                <input type="text" name="price" id="price"><br>
                <label for="rooms">Megjegyzés</label>
                <input type="text" name="comment" id="comment">
                <hr>
                <button type="submit" name="btn-save">
                    <i class="fa fa-save"></i>&nbsp;Mentés
                </button>
                <a href="/rooms"><i class="fa fa-cancel">                    
                    </i>&nbsp;Mégse
                </a>
            </fieldset>
        </form>
    HTML;