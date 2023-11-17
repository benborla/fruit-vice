<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists(dirname(__DIR__) . '/config/bootstrap.php')) {
    require dirname(__DIR__) . '/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

// @INFO: Setup database for "test" environment
$env = 'test';

// @INFO: Drop database if exist
passthru(
    sprintf(
        'php bin/console doctrine:database:drop --if-exists --force --env=%s --quiet',
        $env
    )
);

// @INFO: Re-create database
passthru(
    sprintf(
        'php bin/console doctrine:database:create --if-not-exists --env=%s --quiet',
        $env
    )
);

// @INFO: Add the schemas
passthru(
    sprintf(
        'php bin/console doctrine:schema:create --env=%s --quiet',
        $env
    )
);

// @INFO: Load fake data
// @TODO: This is only recommended to small database, we should resort
// to another strategy when loading fake data when in test environment.
passthru(
    sprintf(
        'php bin/console doctrine:fixtures:load --append -q --env=%s --quiet',
        $env
    )
);
