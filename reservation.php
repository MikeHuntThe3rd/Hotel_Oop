<?php
require_once 'html.php';
require_once 'handler.php';



class reservations extends handler {
    public static function header($title = "Iskola") {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="hu">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="/css/hotel.css" rel="stylesheet" style="text/css">
            <title>$title</title>
        </head>
        <body>
        HTML;
        self::body(); 
        echo '<div class="container">';
    }
    public static function body() {
        echo <<<HTML
        <h3>Reservations</h3>
            
        HTML;
        $table = "<form method='post' action= '/reservation.php'>";
        $table  .= "<table id='reservation'>";
        $table  .= "<tr>";
        foreach(db::table("reservation")[1] as $col_name){
            $table .= "<td>".$col_name['COLUMN_NAME']."</td>";
        }
        $table .= "<td><button name='add'>Add</button></td>";
        $table .= "</tr>";
        foreach(db::table("reservation")[0] as $data){
            $table .= "<tr>";
            foreach($data as $seperated){
                $table .= "<td>".$seperated."</td>";
            } 
            $table .= "<td><button name='edit' style='background-image: url(\"writing.png\"); width: 35px; height: 35px; background-size: contain;' value='".(int)$data['id']."'></button></td>";
            $table .= "<td><button name='delete' style='background-image: url(\"trash.png\"); width: 35px; height: 35px; background-size: contain;' value='".(int)$data['id']."'></button></td>";
            $table .= "</tr>";
        }
        $table .= "</table>";
        $table .= "</form>";
        echo $table;
        if(isset($_POST['edit'])){
            $id = (int)$_POST['edit'];
            db::edit("reservation", $id);
            echo "<script>document.getElementById('reservation').style.display = 'none';</script>";
        }
        if(isset($_POST['add'])){
            db::add("reservation");
            echo "<script>document.getElementById('reservation').style.display = 'none';</script>";
        }
    }
}

if($_SERVER['REQUEST_URI'] == "/reservation.php"){
    $page = new reservations();
    $page->body();
    handler::post_handler();

}