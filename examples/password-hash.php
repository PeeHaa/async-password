<?php declare(strict_types=1);

use Amp\Loop;
use function PeeHaa\AsyncPassword\password_hash;

require_once __DIR__ . '/../vendor/autoload.php';

Loop::run(function() {
    $timer = Loop::repeat(100, function () {
        print "Working...\n";
    });

    var_dump(yield password_hash('my password', PASSWORD_DEFAULT, ['cost' => 14]));

    Loop::cancel($timer);
});
