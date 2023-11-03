<?php

namespace T4\tests\EnvInject;

use PHPUnit\Framework\TestCase;
use T4\EnvInject\EnvInject;

/**
 * @coversDefaultClass \T4\EnvInject\EnvInject
 */
class EnvInjectTest extends TestCase
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
     * @dataProvider interpolateDataProvider
     */
    public function testInterpolateWithPutEnv(string $expect, string $input, array $env): void
    {
        foreach ($env as $key => $value) {
            putenv("$key=$value");
        }
        $this->assertSame($expect, EnvInject::interpolate($input));
    }

    /**
     * @covers ::interpolateWithCallback
     */
    public function testInterpolateWithCallback(): void
    {
        putenv('FOO=foo');
        $actual = EnvInject::interpolateWithCallback(
            '${FOO} bar ${BAR} ${BAR:-defaultbar}',
            function ($value, string $name) {
                if ($name === 'BAR') {
                    return strtoupper($value);
                }
                if ($value === 'foo') {
                    return 'foo!';
                }
                return $value;
            }
        );
        $this->assertSame('foo! bar ${BAR} DEFAULTBAR', $actual);
    }

    public static function interpolateDataProvider(): iterable {
        yield 'no interpolation' => [
            'foo',
            'foo',
            ['FOO' => 'foo']
        ];
        yield 'interpolated' => [
            'foo bar foo',
            '${FOO} ${BAR} ${FOO}',
            ['FOO' => 'foo', 'BAR' => 'bar']
        ];
        yield 'keep env vars that do not exist' => [
            'foo ${BAR_DOES_NOT_EXITS} foo',
            'foo ${BAR_DOES_NOT_EXITS} ${FOO}',
            ['FOO' => 'foo']
        ];
        yield 'interpolate env variables with empty value' => [
            'foo',
            '${BAR_EMPTY}${FOO}',
            ['FOO' => 'foo', 'BAR_EMPTY' => '']
        ];
        yield 'interpolate env variables with null value' => [
            'foo',
            '${BAR_EMPTY}${FOO}',
            ['FOO' => 'foo', 'BAR_EMPTY' => null]
        ];
        yield 'default value is interpolated if var does not exist' => [
            'bar',
            '${BAR_DOES_NOT_EXIST:-bar}',
            []
        ];
        yield 'default value is not interpolated if var exists' => [
            'bar',
            '${BAR:-defaultbar}',
            ['BAR' => 'bar']
        ];
    }

}