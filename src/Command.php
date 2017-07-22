<?php declare(strict_types=1);

namespace PeeHaa\AsyncPassword;

class Command
{
    private const BINARY_PATH = __DIR__ . '/../bin';

    private $binary;

    private $parameters = [];

    public function __construct(string $binary, array $parameters = [])
    {
        $this->binary     = realpath(self::BINARY_PATH . '/' . $binary);
        $this->parameters = $parameters;
    }

    public function getCommandString(): string
    {
        return sprintf('%s %s %s', $this->getPhpBinary(), $this->getBinary(), $this->getParameters());
    }

    private function getPhpBinary(): string
    {
        $binary = escapeshellarg(PHP_BINARY);

        if (PHP_SAPI === 'phpdbg') {
            $binary .= ' -b -qrr --';
        }

        return $binary;
    }

    private function getBinary(): string
    {
        return escapeshellarg($this->binary);
    }

    private function getParameters(): string
    {
        return base64_encode(json_encode($this->parameters));
    }

    public function getParameter(string $name)
    {
        if (!array_key_exists($name, $this->parameters)) {
            throw new \Exception('Invalid parameter');
        }

        return $this->parameters[$name];
    }
}
