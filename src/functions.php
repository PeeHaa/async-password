<?php declare(strict_types=1);

namespace PeeHaa\AsyncPassword;

use function Amp\call;
use Amp\Process\Process;
use Amp\Promise;

function password_hash(string $password, int $algo, array $options = []): Promise
{
    $command = new Command('password_hash.php', [
        'password' => $password,
        'algo'     => $algo,
        'options'  => $options,
    ]);

    return call(function() use ($command) {
        $process = new Process($command->getCommandString(), null, [], ['bypass_shell' => true]);

        $process->start();

        $exitCode = yield $process->join();

        if ($exitCode === 0) {
            return yield $process->getStdout()->read();
        }

        switch ($exitCode) {
            case 101:
                throw new \BadMethodCallException();

            case 102:
            case 103:
                throw new \InvalidArgumentException();

            case 104:
                trigger_error(
                    'password_hash(): Unknown password hashing algorithm: ' . $command->getParameter('algo'),
                    E_WARNING
                );
                return null;

            case 105:
                trigger_error(
                    'password_hash(): Invalid bcrypt cost parameter specified: ' . (int) $command->getParameter('options')['cost'],
                    E_WARNING
                );
                return null;

            default:
                throw new \Exception('Unknown error occurred.');
        }
    });
}

function password_verify(string $password, string $hash): Promise
{
    $command = new Command('password_verify.php', [
        'password' => $password,
        'hash'     => $hash,
    ]);

    return call(function() use ($command) {
        $process = new Process($command->getCommandString(), null, [], ['bypass_shell' => true]);

        $process->start();

        $exitCode = yield $process->join();

        switch ($exitCode) {
            case 101:
                throw new \BadMethodCallException();

            case 102:
            case 103:
                throw new \InvalidArgumentException();
        }

        return $exitCode === 0;
    });
}
