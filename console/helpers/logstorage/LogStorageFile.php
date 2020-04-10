<?php

namespace console\helpers\logstorage;


class LogStorageFile extends AbstractLogStorage
{
    /**
     * Name running command.
     * @var string
     */
    protected $command;

    /**
     * @var resource
     * @internal
     */
    protected $fileHandle;

    /**
     * Path to log file
     * @var string
     * @internal
     */
    protected $logCatalog;

    /**
     * Indicator runned method final.
     * @var bool
     * @internal
     */
    protected $final = false;

    /**
     * LogStorageFile constructor.
     * @param string $logCatalog path to log file.
     */
    public function __construct(string $logCatalog)
    {
        $this->logCatalog = $logCatalog;
    }

    /**
     * @return string
     */
    public function getLogPath()
    {
        return $this->logCatalog . $this->command . '.log';
    }

    /**
     * @param string $command
     * @return void
     */
    public function init(string $command)
    {
        $this->command = $command;

        $logPath = $this->getLogPath();

        $this->fileHandle = fopen($logPath, 'a');
        chmod($logPath, 0777);
    }

    /**
     * Write data to storage.
     * @param string $message
     * @return mixed|void
     */
    public function write(string $message)
    {
        fputs($this->fileHandle, $message . "\n");
    }

    /**
     * Close and remove file.
     * @param string $status
     * @return void
     */
    public function final(string $status = 'fail')
    {
        $this->final = true;

        $this->write('Script completed with status ' . $status);
        fclose($this->fileHandle);
        unlink($this->getLogPath());
    }

    /**
     * Guarantee to run method - final.
     */
    public function __destruct()
    {
        if ($this->final == false) {
            $this->final();
        }
    }
}