<?php

require __DIR__ . '/../vendor/autoload.php';

if (!class_exists('Tester\Assert')) {
    echo "Install Nette Tester using `composer update --dev`\n";
    exit(1);
}

Tester\Environment::setup();

$configurator = new Nette\Configurator;
$configurator->setTimeZone('Europe/Prague');
$configurator->setDebugMode(FALSE);
$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
    ->addDirectory(__DIR__ . '/../app')
    ->register();


$configurator->enableTracy(__DIR__ . '/../log');

$configurator->addConfig(__DIR__ . '/../app/config/common.neon');
$configurator->addConfig(__DIR__ . '/../app/config/local.neon');

return $configurator->createContainer();

