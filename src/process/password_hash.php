<?php declare(strict_types=1);

if (count($argv) !== 2) {
    exit(1);
}

$data = json_decode(base64_decode($argv[1]), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    exit(2);
}

if (!isset($data['password'], $data['algo'], $data['options'])) {
    exit(3);
}

echo password_hash($data['password'], $data['algo'], $data['options']);
