<?php declare(strict_types=1);

if (count($argv) !== 2) {
    exit(101);
}

$data = json_decode(base64_decode($argv[1]), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    exit(102);
}

if (!isset($data['password'], $data['hash'])) {
    exit(103);
}

if (!password_verify($data['password'], $data['hash'])) {
    exit(104);
}
