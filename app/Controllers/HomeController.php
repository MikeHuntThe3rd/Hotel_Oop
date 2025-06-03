<?php

namespace App\Controllers;

use App\Views\View;

class HomeController
{
    static function index()
    {
        View::render('layouts/index');
    }
}