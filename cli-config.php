<?php

require_once __DIR__ . '/bootstrap.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;

$em = $app['orm.em'];

return ConsoleRunner::createHelperSet($em);
