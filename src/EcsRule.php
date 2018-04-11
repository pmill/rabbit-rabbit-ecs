<?php
namespace pmill\RabbitRabbitEcs;

use Aws\Ecs\EcsClient;
use pmill\RabbitRabbit\AbstractRule;

class EcsRule extends AbstractRule
{
    /**
     * @var EcsClient
     */
    protected $ecsClient;

    /**
     * @var string
     */
    protected $ecsClusterName;

    /**
     * @var string
     */
    protected $ecsServiceName;

    /**
     * @var int
     */
    protected $taskCount;

    /**
     * EcsRule constructor.
     *
     * @param string $vHostName
     * @param string $queueName
     * @param EcsClient $ecsClient
     * @param string $ecsClusterName
     * @param string $ecsServiceName
     * @param $taskCount
     */
    public function __construct(
        string $vHostName,
        string $queueName,
        EcsClient $ecsClient,
        string $ecsClusterName,
        string $ecsServiceName,
        $taskCount
    ) {
        $this->ecsClient = $ecsClient;
        $this->ecsClusterName = $ecsClusterName;
        $this->ecsServiceName = $ecsServiceName;
        $this->taskCount = $taskCount;

        parent::__construct($vHostName, $queueName);
    }

    /**
     * @param int $readyMessageCount
     */
    public function run(int $readyMessageCount): void
    {
        $this->ecsClient->updateService([
            'cluster' => $this->ecsClusterName,
            'desiredCount' => $this->taskCount,
            'service' => $this->ecsServiceName,
        ]);
    }
}
