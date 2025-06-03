<?php

namespace App;

class Config
{
    private static array $cache = [];

    public static function get(string $key, $default = null)
    {
        $keys = explode('.', $key); // Split the key by dot notation
        $file = array_shift($keys); // Extract the file name from the first part of the key

        if (!isset(self::$cache[$file])) {
            $path = __DIR__ . "/../config/{$file}.php";

            if (!file_exists($path)) {
                throw new \Exception("Configuration file '{$file}.php' not found.");
            }

            self::$cache[$file] = include $path; // Cache the configuration file
        }

        $config = self::$cache[$file];

        // Traverse through the nested configuration array
        foreach ($keys as $keyPart) {
            if (isset($config[$keyPart])) {
                $config = $config[$keyPart];
            } else {
                return $default; // Return default if key not found
            }
        }

        return $config;
    }
}
