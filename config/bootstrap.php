<?php

declare(strict_types=1);

use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\Dotenv\Dotenv;
use Doctrine\Common\Annotations\AnnotationReader;

require dirname(__DIR__) . '/vendor/autoload.php';

// Load cached env vars if the .env.local.php file exists
// Run "composer dump-env prod" to create it (requires symfony/flex >=1.2)
if (
    is_array($env = @include dirname(__DIR__) . '/.env.local.php') &&
    ($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? $env['APP_ENV']) === $env['APP_ENV']
) {
    foreach ($env as $k => $v) {
        $_ENV[$k] = $_ENV[$k] ?? (isset($_SERVER[$k]) && 0 !== strpos($k, 'HTTP_') ? $_SERVER[$k] : $v);
    }
} elseif (!class_exists(Dotenv::class)) {
    throw new RuntimeException(
        'Please run "composer require symfony/dotenv" to load the ".env" files configuring the application.'
    );
} else {
    // load all the .env files
    (new Dotenv(false))->loadEnv(dirname(__DIR__) . '/.env');
}

$_SERVER += $_ENV;
$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = ($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null) ?: 'dev';
$_SERVER['APP_DEBUG'] = $_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? 'prod' !== $_SERVER['APP_ENV'];
$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = (int) $_SERVER['APP_DEBUG'] || filter_var(
    $_SERVER['APP_DEBUG'],
    FILTER_VALIDATE_BOOLEAN
) ? '1' : '0';

AnnotationReader::addGlobalIgnoredName('covers');
AnnotationReader::addGlobalIgnoredName('group');

/**
 * Words which are difficult to inflect need custom
 * rules.  We set these up here so they are consistent across the
 * entire application.
 */
Inflector::rules('singular', [
    'rules' => ['/^aamc(p)crses$/i' => 'aamc\1crs'],
    'uninflected' => ['aamcpcrs'],
]);
Inflector::rules('plural', [
    'rules' => ['/^aamc(p)crs$/i' => 'aamc\1crses'],
    'uninflected' => ['aamcpcrses'],
]);
