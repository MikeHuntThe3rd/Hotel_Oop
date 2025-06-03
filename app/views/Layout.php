<?php

namespace App\Views;

class Layout {
    public static function header($title = "Iskola") {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="hu">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>$title</title>
        
            <!-- Scripts -->
            <!--            <script src="/js/school.js" type="text/javascript"></script>-->
            <!-- Styles -->
            <link href="/css/school.css" rel="stylesheet" type="text/css">
            <link href="/fontawesome/css/all.css" rel="stylesheet" type="text/css">
        </head>
        <body>
        HTML;
        self::navbar(); // Call navbar at the top of the page
        self::handleMessages();
        echo '<div class="container">';
    }

    private static function handleMessages(): void
    {
        $messages = [
            'success_message' => 'success',
            'warning_message' => 'warning',
            'error_message' => 'error',
        ];

        foreach ($messages as $key => $type) {
            if (isset($_SESSION[$key])) {
                Display::message($_SESSION[$key], $type);
                unset($_SESSION[$key]); // Remove the message after displaying
            }
        }
    }
    public static function navbar() {
        echo <<<HTML
        <nav class="navbar">
            <ul class="nav-list">
                <li class="nav-button"><a href="/"><button style="button" title="Kezdőlap">Kezdőlap</button></a></li>
                <li class="nav-button"><a href="/rooms"><button style="button" title="Szobák">Szobák</button></a></li>
                <li class="nav-button"><a href="/guests"><button style="button" title="Vendégek">Vendégek</button></a></li>
                <li class="nav-button"><a href="/reservations"><button style="button" title="Foglalások">Foglalások</button></a></li>
            </ul>
        </nav>
        HTML;
    }

    public static function sidebar() {
        echo <<<HTML
        <aside>
            <h3>Sidebar</h3>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Settings</a></li>
            </ul>
        </aside>
        HTML;
    }

    public static function footer() {
        echo <<<HTML
        </div> <!-- Closing container -->
            <footer> 
                <hr>
                <p>2025 &copy; Simák Balázs István</p>
            </footer>
        </body>
        </html>
        HTML;
    }
}
