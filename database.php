<?php
require_once 'html.php';
class db extends Layout {

    public static function db_create($server = "localhost", $user = "root", $pass = "", $db = "hotel"):object
    {
        $str = "";
        $myfile = fopen("hotel.sql", "r") or die("Unable to open file!");
        while(!feof($myfile)){
            $str .= fgets($myfile);
        }
        fclose($myfile);
        $conn = mysqli_connect($server, $user, $pass, "");
        $exists = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'hotel';");
        $data = $exists->fetch_assoc();
        if($data == null){
            $conn = mysqli_connect($server, $user, $pass, "");
            $conn->multi_query($str);
            
        }
        $conn = mysqli_connect($server, $user, $pass, $db);
        return  $conn;
    }
    public static function table($table){
        $conn = db::db_create();
        $data = $conn->query("SELECT * FROM ".$table.";");
        $cols = $conn->query("SELECT COLUMN_NAME FROM information_schema.columns WHERE TABLE_NAME = '".$table."';");
        $conn->close();
        return array($data, $cols);
    }
    public static function edit($curr_table, $id){
        $data = db::table($curr_table);
        $html = "<form method='post' action='/".$curr_table.".php'>";
        $cols = $data[1]->fetch_all();
        foreach($data[0]->fetch_all() as $row){
            if($row[0] == $id){
                for($i = 1; $i < count($row); $i++){
                    $html .= "<label for='".$cols[$i][0]."'>".$cols[$i][0].": </label>";
                    $html .= "<input type='text' name='".$cols[$i][0]."' id='".$cols[$i][0]."' value='".$row[$i]."'>";
                }
            }
        }
        $html .= "<button name='edit_save' value='".$id."'>save</button>";
        $html .= "<button name='nvm'>nvm</button>";
        $html .= "</form>";
        echo $html;

    }
    public static function add($curr_table){
        $data = db::table($curr_table);
        $html = "<form method='post' action='/".$curr_table.".php'>";
        $cols = $data[1]->fetch_all();
        for($i = 1; $i < count($cols); $i++){
            $html .= "<label for='".$cols[$i][0]."'>".$cols[$i][0].": </label>";
            $html .= "<input type='text' name='".$cols[$i][0]."' id='".$cols[$i][0]."' value=''>";
        }
        $html .= "<button name='add_save'>save</button>";
        $html .= "<button name='nvm'>nvm</button>";
        $html .= "</form>";
        echo $html;

    }
}
$database = new db();
$database->db_create("localhost", "root", "", "hotel");





