<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Phinx\Config\Config as PhinxConfig;
use Phinx\Wrapper\TextWrapper;

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__), '.env');
$dotenv->safeLoad();

$config = new PhinxConfig(require dirname(__DIR__) . '/config/phinx.php');
$wrapper = new TextWrapper($config);

$environment = getenv('DB_DRIVER') === 'sqlite' ? 'sqlite' : 'production';

$result = $wrapper->getMigrate($environment);
if ($result) {
    echo $result;
}
