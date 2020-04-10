<?php

namespace console\helpers\logstorage;

abstract class AbstractLogStorage
{
    /**
     * Initiate storage.
     * @param string $command
     * @return mixed
     */
    abstract public function init(string $command);

    /**
     * Write to storage.
     * @param string $message
     * @return mixed
     */
    abstract public function write(string $message);

    /**
     * Actions is running after command completion.
     * @param string $status
     * @return mixed
     */
    abstract public function final(string $status);

    /**
     * Optional actions is running after write.
     * @param $progressData
     */
    public function afterWrite($progressData) {}
}