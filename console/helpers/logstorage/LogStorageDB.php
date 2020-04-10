<?php

namespace console\helpers\logstorage;


class LogStorageDB extends AbstractLogStorage
{
    /**
     * Name running command.
     * @var string
     */
    protected $command;

    /**
     * @var \yii\db\ActiveRecord
     * @internal
     */
    protected $model;

    /**
     * Indicator runned method final.
     * @var bool
     * @internal
     */
    protected $final = false;

    /**
     * LogStorageDB constructor.
     * @param \yii\db\ActiveRecord $model
     */
    public function __construct(\yii\db\ActiveRecord $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $command
     * @return void
     */
    public function init(string $command)
    {
        $this->command = $command;

        $model = $this->model;
        $model->command = $this->command;
        $model->status = 'running';
        $model->progress = 0;
        $model->save(false);
    }

    /**
     * @param string $message
     * @return void
     */
    public function write(string $message)
    {
        $this->model->addLogMessage($message);
    }

    /**
     * Write data to storage.
     * @param $progressData
     */
    public function afterWrite($progressData)
    {
        // progress update
        $this->model->progress = $progressData['percent'];
        $this->model->save(false);
    }

    /**
     * Final change status
     * @param string $status
     * @return mixed|void
     */
    public function final(string $status = 'fail')
    {
        $this->final = true;

        $this->write('Script completed with status ' . $status);

        $model = $this->model;
        $model->status = $status;
        $model->save(false);
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