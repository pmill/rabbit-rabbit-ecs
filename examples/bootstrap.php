<?php

use Aws\Ecs\EcsClient;
use pmill\RabbitRabbit\Conditions\CountBetween;
use pmill\RabbitRabbit\Conditions\GreaterThan;
use pmill\RabbitRabbit\ConsumerManager;
use pmill\RabbitRabbit\RabbitConfig;
use pmill\RabbitRabbitEcs\EcsRule;

require __DIR__ . '/../vendor/autoload.php';

$config = new RabbitConfig([
    'baseUrl' => 'localhost:15672',
    'username' => 'guest',
    'password' => 'guest',
]);

$manager = new ConsumerManager($config);

$vhostName = '/';
$queueName = 'messages';
$ecsClusterName = 'default';
$ecsServiceName = 'sample-webapp';

$ecsClient = new EcsClient([
    'version' => 'latest',
    'region' => 'eu-west-1',
    'credentials' => [
        'key' => '',
        'secret' => '',
    ],
]);

$rules = [
    1 => ['from' => 0, 'to' => 4999],
    2 => ['from' => 5000, 'to' => 9999],
    6 => ['from' => 10000, 'to' => 24999],
];

foreach ($rules as $taskCount => $ruleOptions) {
    $manager->addRule(
        new EcsRule(
            $vhostName,
            $queueName,
            $ecsClient,
            $ecsClusterName,
            $ecsServiceName,
            $taskCount
        ),
        new CountBetween($ruleOptions['from'], $ruleOptions['to'], true)
    );
}

$manager->addRule(
    new EcsRule(
        $vhostName,
        $queueName,
        $ecsClient,
        $ecsClusterName,
        $ecsServiceName,
        8
    ),
    new GreaterThan(25000)
);

return $manager;