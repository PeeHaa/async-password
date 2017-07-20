<?php declare(strict_types=1);

namespace PeeHaa\AsyncPassword;

use function Amp\call;
use Amp\Process\Process;
use Amp\Promise;

function password_hash(string $password, int $algo, array $options = []): Promise
{
    $parameters = base64_encode(json_encode([
        'password' => $password,
        'algo'     => $algo,
        'options'  => $options,
    ]));

    return call(function() use ($parameters) {
        $phpFile = escapeshellarg(realpath(__DIR__ . '/../bin/password_hash.php'));

        $process = new Process("php $phpFile $parameters");

        $process->start();

        $exitCode = yield $process->join();

        switch ($exitCode) {
            case 1:
                throw new \BadMethodCallException();

            case 2:
            case 3:
                throw new \InvalidArgumentException();
        }

        return yield $process->getStdout()->read();
    });
}

function password_verify(string $password, string $hash): Promise
{
    $parameters = base64_encode(json_encode([
        'password' => $password,
        'hash'     => $hash,
    ]));

    return call(function() use ($parameters) {
        $phpFile = escapeshellarg(realpath(__DIR__ . '/../bin/password_verify.php'));

        $process = new Process("php $phpFile $parameters");

        $process->start();

        $exitCode = yield $process->join();

        switch ($exitCode) {
            case 1:
                throw new \BadMethodCallException();

            case 2:
            case 3:
                throw new \InvalidArgumentException();
        }

        return $exitCode === 0;
    });
}
