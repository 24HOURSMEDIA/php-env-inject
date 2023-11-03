<?php
declare(strict_types=1);

namespace T4\EnvInject;

/**
 * The EnvInject class provides utility methods for working with environment variables.
 * It allows for interpolation of environment variables within strings.
 */
class EnvInject
{
    /**
     * Interpolates environment variables into a given string.
     *
     * Environment variables are specified in the string in the format: ${VAR_NAME}
     * If the environment variable is not set, and a default value is provided in the format: ${VAR_NAME:-default_value},
     * the default value will be used.
     *
     * @param string $string The string with environment variables to be interpolated.
     * @return string The interpolated string with environment variables replaced by their values.
     */
    public static function interpolate(string $string): string
    {
        return preg_replace_callback('/\$\{([A-Za-z0-9_]+)(:-([^}]*))?}/', function($matches) {
            $envValue = getenv($matches[1]);
            if ($envValue !== false) {
                // Return the environment variable's value if it exists
                return $envValue;
            } elseif (isset($matches[3])) {
                // Return the default value specified after the ':-' if the environment variable is not set
                return $matches[3];
            } else {
                // Return the original placeholder if no environment variable or default value is found
                return $matches[0];
            }
        }, $string);
    }
}
