<?php

namespace KK\ListenWith\Connections;

use Predis\Command\CommandInterface;
use Predis\Connection\Aggregate\MasterSlaveReplication;
use Predis\Connection\NodeConnectionInterface;
use Predis\Replication\MissingMasterException;

class StandardReplication extends MasterSlaveReplication
{
    /**
     * @param CommandInterface $command
     * @return NodeConnectionInterface
     * @throws MissingMasterException
     * @throws \Predis\NotSupportedException
     */
    public function getConnection(CommandInterface $command): NodeConnectionInterface
    {
        // Use slave if command is readonly
        if ($this->strategy->isReadOperation($command) && $slave = $this->pickSlave()) {
            $this->current = $slave;
        } else {
            $this->current = $this->getMasterOrDie();
        }

        return $this->current;
    }

    /**
     * Returns the connection associated to the master server.
     *
     * @return NodeConnectionInterface
     * @throws MissingMasterException
     */
    private function getMasterOrDie(): NodeConnectionInterface
    {
        if (!$connection = $this->getMaster()) {
            throw new MissingMasterException('No master server available for replication');
        }

        return $connection;
    }
}