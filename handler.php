<?php


require_once 'database.php';
class handler extends db {
    public static function post_handler(){
        $uri = $_SERVER['REQUEST_URI'];
        switch($uri){
            case '/guests.php':
                $uri = "guests";
                break;
            case '/reservation.php':
                $uri = "reservation";
                break;
            case '/rooms.php':
                $uri = "rooms";
                break;
        }
        if(isset($_POST['edit_save'])){
            $id = (int)$_POST['edit_save'];
            handler::post_edit($uri, $id);
        }
        if(isset($_POST['add_save'])){
            handler::post_add($uri);
        }
        if(isset($_POST['delete'])){
            $id = (int)$_POST['delete'];
            handler::post_delete($uri, $id);
        }
    }
    public static function post_edit($uri, $id){
        $conn = db::db_create();
        $col_names = db::table($uri)[1]->fetch_all();
        $datatypes = $conn->query("SELECT DATA_TYPE FROM information_schema.COLUMNS WHERE TABLE_NAME = '".$uri."';")->fetch_all();
        $inputs = [];
        for($i = 1; $i < count(db::table($uri)[0]->fetch_all()[0]);$i++){
            if($_POST[$col_names[$i][0]] == ""){
                header("Location: /".$uri.".php");
                return;
            }
            else{
                $inputs[] = $_POST[$col_names[$i][0]];
            }
        }
        for($j = 0; $j < count($inputs); $j++){
            switch($datatypes[$j + 1][0]){
                case "date":
                    if(date('Y-m-d', strtotime($inputs[$j])) != "0000-00-00"){
                        $cell_data = "'".date('Y-m-d', strtotime($inputs[$j]))."'";
                    }
                    else{
                        header("Location: /".$uri.".php");
                        return;
                    }
                    break;
                case "int":
                    if(ctype_digit($inputs[$j])){
                        $cell_data = $inputs[$j];
                    }
                    else{
                        header("Location: /".$uri.".php");
                        return;
                    }
                    break;
                case "varchar":
                    if(ctype_digit($inputs[$j])){
                        $cell_data = "'". $inputs[$j] ."'";
                    }
                    else{
                        header("Location: /".$uri.".php");
                        return;
                    }
                    break;
            }
            $conn->query("UPDATE ".$uri." SET ".$uri.".".$col_names[$j + 1][0]." = ".$cell_data." WHERE ".$uri.".id = ".$id."");
        }
        $conn->close();
        header("Location: /".$uri.".php");
    }
    public static function post_add($uri){
        $conn = db::db_create();
        $col_names = db::table($uri)[1]->fetch_all();
        $datatypes = $conn->query("SELECT DATA_TYPE FROM information_schema.COLUMNS WHERE TABLE_NAME = '".$uri."';")->fetch_all();
        $inputs = [];
        for($i = 1; $i < count($col_names);$i++){
            if($_POST[$col_names[$i][0]] == ""){
                header("Location: /".$uri.".php");
                return;
            }
            else{
                $inputs[] = $_POST[$col_names[$i][0]];
            }
        }
        $query1 = "INSERT INTO ".$uri."(";
        $query2 = "VALUES(";
        for($j = 0; $j < count($inputs); $j++){
            switch($datatypes[$j + 1][0]){
                case "date":
                    if(date('Y-m-d', strtotime($inputs[$j])) != "0000-00-00"){
                        $cell_data = "'".date('Y-m-d', strtotime($inputs[$j]))."'";
                    }
                    else{
                        header("Location: /".$uri.".php");
                        return;
                    }
                    break;
                case "int":
                    if(ctype_digit($inputs[$j])){
                        $cell_data = $inputs[$j];
                    }
                    else{
                        header("Location: /".$uri.".php");
                        return;
                    }
                    break;
                case "varchar":
                    if(ctype_digit($inputs[$j])){
                        $cell_data = "'". $inputs[$j] ."'";
                    }
                    else{
                        header("Location: /".$uri.".php");
                        return;
                    }
                    break;
            }
            $query1 .= $col_names[$j + 1][0];
            $query2 .= $cell_data; 
            if ($j < count($inputs)-1){
                $query1 .= ', ';
                $query2 .= ', ';
            }
        }
        $query1 .= ") ";
        $query2 .= ");";
        $conn->query($query1 . $query2);
        $conn->close();
        header("Location: /".$uri.".php");
    }
    public static function post_delete($uri, $id){
        $conn = db::db_create();
        $conn->query("DELETE FROM ".$uri." WHERE ".$uri.".id = ".$id."");
        $conn->close();
        header("Location: /".$uri.".php");
    }
}