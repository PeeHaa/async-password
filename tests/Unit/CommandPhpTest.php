<?php declare(strict_types=1);

namespace PeeHaa\AsyncPasswordTests;

use PeeHaa\AsyncPassword\Command;
use PHPUnit\Framework\TestCase;

class CommandPhpTest extends TestCase
{
    private $phpFile;

    private $emptyParameters;

    private $outputFormat;

    public function setUp()
    {
        if (PHP_SAPI === 'phpdbg') {
            $this->markTestSkipped('Running using phpdbg.');

            return;
        }

        $this->phpFile         = realpath(__DIR__ . '/../../bin/password_hash.php');
        $this->emptyParameters = base64_encode(json_encode([]));

        if (stripos(PHP_OS, "win")) {
            $this->outputFormat = '"%s" "%s" %s';
        } else {
            $this->outputFormat = "'%s' '%s' %s";
        }
    }

    public function testGetCommandStringWithoutParameters()
    {
        $command = new Command('password_hash.php');

        $expected = sprintf($this->outputFormat, PHP_BINARY, $this->phpFile, $this->emptyParameters);

        $this->assertSame($expected, $command->getCommandString());
    }

    public function testGetCommandStringWithParameters()
    {
        $command = new Command('password_hash.php', ['foo' => 'bar']);

        $expected = sprintf($this->outputFormat, PHP_BINARY, $this->phpFile, base64_encode(json_encode(['foo' => 'bar'])));

        $this->assertSame($expected, $command->getCommandString());
    }

    public function testGetCommandStringWithParametersEncodesCorrectly()
    {
        $command = new Command('password_hash.php', ['foo' => 'bar']);

        $parts = explode(' ', $command->getCommandString());

        $encodedParameters = json_decode(base64_decode(end($parts)), true);

        $this->assertSame(['foo' => 'bar'], $encodedParameters);
    }
}
