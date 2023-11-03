<?php

namespace T4\tests\EnvInject;

use PHPUnit\Framework\TestCase;
use T4\EnvInject\JsonEnvInject;

/**
 * @coversDefaultClass \T4\EnvInject\JsonEnvInject
 */
class JsonEnvInjectTest extends TestCase
{

    private array $originalEnvVars;

    protected function setUp(): void
    {
        // Back up environment variables
        $this->originalEnvVars = getenv();
    }

    protected function tearDown(): void
    {
        foreach (getenv() as $key => $value) {
            putenv("$key");
        }

        // Restore environment variables
        foreach ($this->originalEnvVars as $name => $value) {
            if ($value === false) {
                putenv($name); // Unset the environment variable if it wasn't set before
            } else {
                putenv("$name=$value");
            }
        }
    }

    /**
     * @covers ::interpolate
     */
    public function testInterpolate(): void
    {
        $value = 'f"o"o';
        $jsonString = '{"foo":"${FOO}"}';
        putenv('FOO=' . $value);
        $actual = JsonEnvInject::interpolate($jsonString);
        $decoded = json_decode($actual, true);
        $this->assertSame($value, $decoded['foo']);
    }

}