<?php

$tableBody = "";
foreach ($rooms as $room) {
    $tableBody .= <<<HTML
            <tr>
                <td>{$room->floor}</td>
                <td>{$room->room_number}</td>
                <td>{$room->accommodation}</td>
                <td style="padding-right: 10px;">{$room->price}Ft</td>
                <td>{$room->comment}</td>
                <td class='flex float-right'>
                    <form method='post' action='/rooms/edit'>
                        <input type='hidden' name='id' value='{$room->id}'>
                        <button type='submit' name='btn-edit' title='Módosít'><i class="fa-solid fa-file-pen"></i></button>
                    </form>
                    <form method='post' action='/rooms'>
                        <input type='hidden' name='id' value='{$room->id}'>    
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
                    <th>Emelet</th>
                    <th>Szobaszám</th>
                    <th>Férőhelyek</th>
                    <th>Ár</th>
                    <th>Megjegyzés</th>
                    <th>
                        <form method='post' action='/rooms/create'>
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
