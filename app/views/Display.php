<?php

namespace App\Views;

class Display
{
    const TEXT = 'text';
    const INFO = 'info';
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const DANGER = 'danger';
    const ERROR = 'error';
    const STYLES = [
        // text: Átlátszó háttér, fekete szöveg.
        'text' => 'background-color: transparent; color: #000; border: 1px solid #ddd;',
        // info: Halványkék háttér, sötétkék szöveg.
        'info' => 'background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb;',
        // success: Halványzöld háttér, zöld szöveg.
        'success' => 'background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;',
        // warning: Halványsárga háttér, sötétsárga szöveg.
        'warning' => 'background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba;',
        // danger és error: Halvány piros háttér, piros szöveg.
        'danger' => 'background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;',
        'error' => 'background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;'
    ];
    static function message($message, $type = 'text', $important = false) {
        $fontWeight = '';
        if ($important) {
            $fontWeight = ' font-weight: bold;';
        }

        // Ellenőrizzük, hogy a megadott típus létezik-e
        $style = self::STYLES[$type] ?? self::STYLES['text'];

        // Kiírjuk az üzenetet a megfelelő stílussal
        ob_start();
        echo "
            <div style='position: relative; padding: 10px; margin: 10px 0; border-radius: 4px; $style $fontWeight'>
                <button onclick='this.parentElement.style.display=\"none\";' 
                        style='position: absolute; top: 5px; right: 10px; background: none; border: none; font-size: 16px; cursor: pointer;'>
                    &times;
                </button>
                $message
            </div>";
        $output = ob_get_clean();
        echo $output;
    }
}