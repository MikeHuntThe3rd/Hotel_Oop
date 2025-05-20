<?php

require_once 'html.php';
require_once 'handler.php';

class rooms extends handler {
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
        <h3>Rooms</h3>
            <table>
        HTML;
        $table = "<form method='post' action= '/rooms.php'>";
        $table  .= "<table id='rooms'>";
        $table  .= "<tr>";
        foreach(db::table("rooms")[1] as $col_name){
            $table .= "<td>".$col_name['COLUMN_NAME']."</td>";
        }
        $table .= "<td><button name='add'>Add</button></td>";
        $table .= "</tr>";
        foreach(db::table("rooms")[0] as $data){
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
            db::edit("rooms", $id);
            echo "<script>document.getElementById('rooms').style.display = 'none';</script>";
        }
        if(isset($_POST['add'])){
            db::add("rooms");
            echo "<script>document.getElementById('rooms').style.display = 'none';</script>";
        }
    }
}

if($_SERVER['REQUEST_URI'] == "/rooms.php"){
    $page = new rooms();
    $page->body();
    handler::post_handler();

}