<?php declare(strict_types=1);

use Amp\Loop;
use function PeeHaa\AsyncPassword\password_verify;

require_once __DIR__ . '/../vendor/autoload.php';

Loop::run(function() {
    $timer = Loop::repeat(100, function () {
        print "Working...\n";
    });

    var_dump(yield password_verify('my password', '$2y$14$Vnf8TrxocumxuUWKPXoNU.vclUoJbiPANDMo27Da7y5jLjbH/brdq'));

    var_dump(yield password_verify('my password', '$2y$14$Vnf8TrxocumxuUWKPXoNU.vclUoJbiPANDMo27Da7y5jLjbH/xxxx'));

    Loop::cancel($timer);
});
