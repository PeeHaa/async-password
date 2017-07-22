<?php

namespace PeeHaa\AsyncPasswordTests;

use function Amp\Promise\wait;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;
use function PeeHaa\AsyncPassword\password_hash;

class PasswordHashWeakTest extends TestCase
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
        // note: the native function just accepts null instead of throwing an TypeError
        // little I can do about this unless getting really hacky
        $this->expectException(\TypeError::class);

        wait(password_hash(null, PASSWORD_BCRYPT));
    }

    public function testIntegerBehavior()
    {
        $hash = wait(password_hash(12345, PASSWORD_BCRYPT, ['cost' => 12]));

        $this->assertRegExp('~^\$2y\$12\$~', $hash);
    }

    public function testInvalidAlgo()
    {
        // note: the native function just spits out a warning instead of throwing an TypeError
        // little I can do about this unless getting really hacky
        $this->expectException(\TypeError::class);

        wait(password_hash('foo', []));
    }

    public function testInvalidAlgo2()
    {
        $this->expectException(Error::class);

        wait(password_hash('foo', 2));
    }

    public function testInvalidAlgo2ReturnsNull()
    {
        // stfu operator to prevent phpunit from converting to an exception
        $hash = @wait(password_hash('foo', 2));

        $this->assertNull($hash);
    }

    public function testInvalidPassword()
    {
        // note: the native function just spits out a warning instead of throwing an TypeError
        // little I can do about this unless getting really hacky
        $this->expectException(\TypeError::class);

        wait(password_hash([], 1));
    }

    public function testInvalidBcryptCostLow()
    {
        $this->expectException(Error::class);

        wait(password_hash('foo', PASSWORD_BCRYPT, ['cost' => 3]));
    }

    public function testInvalidBcryptCostLowReturnsNull()
    {
        // stfu operator to prevent phpunit from converting to an exception
        $hash = @wait(password_hash('foo', PASSWORD_BCRYPT, ['cost' => 3]));

        $this->assertNull($hash);
    }

    public function testInvalidBcryptCostHigh()
    {
        $this->expectException(Error::class);

        wait(password_hash('foo', PASSWORD_BCRYPT, ['cost' => 32]));
    }

    public function testInvalidBcryptCostHighReturnsNull()
    {
        // stfu operator to prevent phpunit from converting to an exception
        $hash = @wait(password_hash('foo', PASSWORD_BCRYPT, ['cost' => 32]));

        $this->assertNull($hash);
    }

    public function testInvalidBcryptCostInvalid()
    {
        $this->expectException(Error::class);

        wait(password_hash('foo', PASSWORD_BCRYPT, ['cost' => 'foo']));
    }

    public function testInvalidBcryptCostInvalidReturnsNull()
    {
        // stfu operator to prevent phpunit from converting to an exception
        $hash = @wait(password_hash('foo', PASSWORD_BCRYPT, ['cost' => 'foo']));

        $this->assertNull($hash);
    }
}
