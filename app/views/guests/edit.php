<?php
$html = <<<HTML
    <form method='post' action='/guests'>
        <input type='hidden' name='_method' value='PATCH'>
        <input type="hidden" name="id" value="{$guest->id}">
        <fieldset>
            <label for="guest">Vendég</label>
            <input type="text" name="name" id="name" value="{$guest->name}"><br>
            <label for="guest">Életkor</label>
            <input type="text" name="age" id="age" value="{$guest->age}">
            <hr>
            <button type="submit" name="btn-update"><i class="fa fa-save">                    
                </i>&nbsp;Mentés
            </button>
            <a href="/guests"><i class="fa fa-cancel"></i>&nbsp;Mégse
            </a>
        </fieldset>
    </form>
HTML;

echo $html;