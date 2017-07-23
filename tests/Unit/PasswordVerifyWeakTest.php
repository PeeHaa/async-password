<?php

namespace PeeHaa\AsyncPasswordTests;

use function Amp\Promise\wait;
use PHPUnit\Framework\TestCase;
use function PeeHaa\AsyncPassword\password_verify;

class PasswordVerifyWeakTest extends TestCase
{
    public function testFuncExists()
    {
        $this->assertTrue(function_exists('Peehaa\AsyncPassword\password_verify'));
    }

    public function testFailedType()
    {
        $this->assertFalse(wait(password_verify(123, 123)));
    }

    public function testSaltOnly()
    {
        $result = wait(password_verify('foo', '$2a$07$usesomesillystringforsalt$'));

        $this->assertFalse($result);
    }

    public function testInvalidPassword()
    {
        $result = wait(password_verify('rasmusler', '$2a$07$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi'));

        $this->assertFalse($result);
    }

    public function testValidPassword()
    {
        $result = wait(password_verify('rasmuslerdorf', '$2a$07$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi'));

        $this->assertTrue($result);
    }

    public function testInValidHash()
    {
        $result = wait(password_verify('rasmuslerdorf', '$2a$07$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hj'));

        $this->assertFalse($result);
    }
}
