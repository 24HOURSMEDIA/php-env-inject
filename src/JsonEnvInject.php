<?php
declare(strict_types=1);
namespace T4\EnvInject;

class JsonEnvInject
{

    /**
     * Injects variables json-escaped into a string.
     */
    public static function interpolate(string $string): string
    {
        return EnvInject::interpolateWithCallback($string, function ($value, string $name) {
            return trim(json_encode((string)$value), '"');
        });
    }

}