<?php declare(strict_types=1);

if (count($argv) !== 2) {
    exit(101);
}

$data = json_decode(base64_decode($argv[1]), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    exit(102);
}

if (!isset($data['password'], $data['algo'], $data['options'])) {
    exit(103);
}

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (strpos($errstr, 'password_hash(): Unknown password hashing algorithm: ') === 0) {
        exit(104);
    }

    if (strpos($errstr, 'password_hash(): Invalid bcrypt cost parameter specified: ') === 0) {
        exit(105);
    }
});

echo password_hash($data['password'], $data['algo'], $data['options']);
