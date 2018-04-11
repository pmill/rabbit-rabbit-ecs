<?php

/** @var \pmill\RabbitRabbit\ConsumerManager $manager */
$manager = require_once('bootstrap.php');

while (true) {
    $manager->run();
    sleep(60);
}
