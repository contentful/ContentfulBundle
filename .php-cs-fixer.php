<?php

$config = require __DIR__.'/scripts/php-cs-fixer.php';

return $config(
    'contentful-bundle',
    true,
    ['scripts', 'src', 'tests']
);
