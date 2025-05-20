<?php
require_once 'database.php';
require_once 'guests.php';
require_once 'reservation.php';
require_once 'rooms.php';

class Layout {
    public static function header($title = "Iskola") {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="hu">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="/hotel.css" rel="stylesheet" style="text/css">
            <title>$title</title>
        </head>
        <body>
        HTML;
        self::body(); 
        echo '<div class="container">';
    }

    
    public static function body() {
        echo <<<HTML
        <nav class="navbar">
            <ul class="nav-list">
                <li class="nav-button"><a href="/html.php"><button style="button" title="MainPage">MainPage</button></a></li>
                <li class="nav-button"><a href="/guests.php"><button style="button" title="Guests">Guests</button></a></li>
                <li class="nav-button"><a href="/reservation.php"><button style="button" title="Reservations">Reservations</button></a></li>
                <li class="nav-button"><a href="/rooms.php"><button style="button" title="Rooms">Rooms</button></a></li>
            </ul>
        </nav>
        HTML;
    }
}

$idk = new Layout();
$idk->header();