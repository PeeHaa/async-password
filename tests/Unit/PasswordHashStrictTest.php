<?php declare(strict_types=1);

namespace PeeHaa\AsyncPasswordTests;

use function Amp\Promise\wait;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;
use function PeeHaa\AsyncPassword\password_hash;

class PasswordHashStrictTest extends TestCase
{
    public function testFuncExists()
    {
        $this->assertTrue(function_exists('Peehaa\AsyncPassword\password_hash'));
    }

    public function testStringLength()
    {
        $hash = wait(password_hash('foo', PASSWORD_BCRYPT));

        $this->assertEquals(60, strlen($hash));
    }

    public function testHash()
    {
        $hash = wait(password_hash('foo', PASSWORD_BCRYPT));

        $this->assertEquals($hash, crypt('foo', $hash));
    }

    public function testErrorsOnNull()
    {
        $this->expectException(\TypeError::class);

        wait(password_hash(null, PASSWORD_BCRYPT));
    }

    public function testIntegerBehavior()
    {
        $this->expectException(\TypeError::class);

        wait(password_hash(12345, PASSWORD_BCRYPT, ['cost' => 12]));
    }

    public function testInvalidAlgo()
    {
        $this->expectException(\TypeError::class);

        wait(password_hash('foo', []));
    }

    public function testInvalidAlgo2()
    {
        $this->expectException(Error::class);

        wait(password_hash('foo', 2));
    }

    public function testInvalidPassword()
    {
        $this->expectException(\TypeError::class);

        wait(password_hash([], 1));
    }

    public function testInvalidBcryptCostLow()
    {
        $this->expectException(Error::class);

        wait(password_hash('foo', PASSWORD_BCRYPT, ['cost' => 3]));
    }

    public function testInvalidBcryptCostHigh()
    {
        $this->expectException(Error::class);

        wait(password_hash('foo', PASSWORD_BCRYPT, ['cost' => 32]));
    }

    public function testInvalidBcryptCostInvalid()
    {
        $this->expectException(Error::class);

        wait(password_hash('foo', PASSWORD_BCRYPT, ['cost' => 'foo']));
    }
}
