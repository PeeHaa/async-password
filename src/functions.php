<?php declare(strict_types=1);

namespace PeeHaa\AsyncPassword;

use function Amp\call;
use Amp\Process\Process;
use Amp\Promise;

function password_hash(string $password, int $algo, array $options = []): Promise
{
    $phpFile = escapeshellarg(realpath(__DIR__ . '/process/password_hash.php'));

    $data = [
        'password' => $password,
        'algo'     => $algo,
        'options'  => $options,
    ];

    $encodedData = base64_encode(json_encode($data));

    return call(function() use ($phpFile, $encodedData) {
        $process = new Process("php $phpFile $encodedData");

        $process->start();

        $exitCode = yield $process->join();

        if ($exitCode !== 0) {
            throw new \Exception('Could not hash password (' . $exitCode . ').');
        }

        return yield $process->getStdout()->read();
    });
}

function password_verify(string $password, string $hash): Promise
{
    $phpFile = escapeshellarg(realpath(__DIR__ . '/process/password_verify.php'));

    $data = [
        'password' => $password,
        'hash'     => $hash,
    ];

    $encodedData = base64_encode(json_encode($data));

    return call(function() use ($phpFile, $encodedData) {
        $process = new Process("php $phpFile $encodedData");

        $process->start();

        $exitCode = yield $process->join();

        if ($exitCode !== 0) {
            return false;
        }

        return true;
    });
}
