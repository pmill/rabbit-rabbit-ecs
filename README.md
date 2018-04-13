# pmill/rabbit-rabbit-ecs

## Introduction

This library is an integration for [pmill/rabbit-rabbit](https://github.com/pmill/rabbit-rabbit) allows you to set the 
desired task count for Amazon ECS services when RabbitMQ queues message counts match conditions.

## Requirements

This library package requires PHP 7.1 or later, and a previously setup auto-scaling Amazon ECS service.

## Installation

The recommended way to install is through [Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest version:

```bash
composer require pmill/rabbit-rabbit-ecs
```

# Usage

The following example will set the service desired task count to 1 if the number of messages in the queue is less than 
5000, if the count is greater than 5000 then it will set the desired task count to 3. There is a complete example in the 
`examples/` folder.

```php
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

$manager->addRule(
    new EcsRule(
        $vhostName,
        $queueName,
        $ecsClient,
        $ecsClusterName,
        $ecsServiceName,
        1
    ),
    new CountBetween(0, 4999)
);

$manager->addRule(
    new EcsRule(
        $vhostName,
        $queueName,
        $ecsClient,
        $ecsClusterName,
        $ecsServiceName,
        3
    ),
    new GreaterThan(5000)
);

$manager->run();
```

# Version History

0.1.0 (12/04/2018)

*   First public release of rabbit-rabbit-ecs


# Copyright

pmill/rabbit-rabbit-ecs
Copyright (c) 2018 pmill (dev.pmill@gmail.com) 
All rights reserved.