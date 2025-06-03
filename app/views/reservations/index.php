<?php

use App\Models\GuestModel;

$tableBody = "";
foreach ($reservations as $reservation) {
    $tableBody .= <<<HTML
            <tr>
                <td>{$reservation->getRoom()->room_number}</td>
                <td>{$reservation->getGuest()->name}</td>
                <td>{$reservation->days}</td>
                <td>{$reservation->date}</td>
                <td class='flex float-right'>
                    <form method='post' action='/reservations/edit'>
                        <input type='hidden' name='id' value='{$reservation->id}'>
                        <button type='submit' name='btn-edit' title='Módosít'><i class="fa-solid fa-file-pen"></i></button>
                    </form>
                    <form method='post' action='/reservations'>
                        <input type='hidden' name='id' value='{$reservation->id}'>    
                        <input type='hidden' name='_method' value='DELETE'>
                        <button type='submit' name='btn-del' title='Töröl'><i class="fa-solid fa-eraser"></i></button>
                    </form>
                </td>
            </tr>
            HTML;
}

$html = <<<HTML
        <table id='admin-subjects-table' class='admin-subjects-table'>
            <thead>
                <tr>
                    <th>Szobaszám</th>
                    <th>Vendég neve</th>
                    <th>Napok</th>
                    <th>Dátum</th>
                    <th>
                        <form method='post' action='/reservations/create'>
                            <button type="submit" name='btn-plus' title='Új'>
                                <i class="fa-solid fa-circle-plus"></i>&nbsp;Új</button>
                        </form>
                    </th>
                </tr>
            </thead>
             <tbody>%s</tbody>
            <tfoot>
            </tfoot>
        </table>
        HTML;

echo sprintf($html, $tableBody);
