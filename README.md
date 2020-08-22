# PEAR client

[![CircleCI](https://circleci.com/gh/Sweetchuck/pear-client.svg?style=svg)](https://circleci.com/gh/Sweetchuck/pear-client)
[![codecov](https://codecov.io/gh/Sweetchuck/pear-client/branch/1.x/graph/badge.svg?token=2kV7PSkPX5)](https://codecov.io/gh/Sweetchuck/pear-client)

[PEAR channel server REST interface](https://pear.php.net/manual/en/core.rest.php)

## Usage

```php
<?php

use GuzzleHttp\MessageFormatter;
use Psr\Log\LogLevel;
use Sweetchuck\PearClient\PearClient;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

require_once './vendor/autoload.php';

$logOutput = new ConsoleOutput();
$logVerbosity = [
    LogLevel::EMERGENCY => OutputInterface::VERBOSITY_QUIET,
    LogLevel::ALERT     => OutputInterface::VERBOSITY_QUIET,
    LogLevel::CRITICAL  => OutputInterface::VERBOSITY_QUIET,
    LogLevel::ERROR     => OutputInterface::VERBOSITY_QUIET,
    LogLevel::WARNING   => OutputInterface::VERBOSITY_QUIET,
    LogLevel::NOTICE    => OutputInterface::VERBOSITY_QUIET,
    LogLevel::INFO      => OutputInterface::VERBOSITY_QUIET,
    LogLevel::DEBUG     => OutputInterface::VERBOSITY_QUIET,
];
$logger              = new ConsoleLogger($logOutput, $logVerbosity);
$logMessageFormatter = new MessageFormatter();

$options = [
    'base_uri' => 'https://pecl.php.net/rest/',
    'handler'  => PearClient::createHttpClientHandlerStack(
        null,
        $logger,
        $logMessageFormatter,
    ),
];

$pecl = new PearClient(PearClient::createHttpClient($options));

$categories = $pecl->categoriesGet();
foreach ($categories->list as $category) {
    echo "name: {$category->name}", PHP_EOL;
    echo "href: {$category->href}", PHP_EOL;

    $packages = $pecl->categoryPackagesGet($category->name);
    foreach ($packages->list as $package) {
        echo "name: {$package->name}", PHP_EOL;
        echo "description: {$category->description}", PHP_EOL;

        break;
    }

    break;
}

```
