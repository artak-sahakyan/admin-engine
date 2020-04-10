<?php
namespace common\helpers;

use Yii;
use yii\helpers\VarDumper;
use yii\log\{ DbTarget, LogRuntimeException };

class ErrorLogger extends DbTarget
{
    public $logTable = '{{%error_logs}}';

    public function export()
    {
        if(Yii::$app->params['log']['enable'] == false) {
            return false;
        }
        
        if ($this->db->getTransaction()) {
            $this->db = clone $this->db;
        }
        $tableName = $this->db->quoteTableName($this->logTable);
        $sql = "INSERT INTO $tableName ([[level]], [[category]], [[log_time]], [[prefix]], [[message]], [[url]])
                VALUES (:level, :category, :log_time, :prefix, :message, :url)";
        $command = $this->db->createCommand($sql);
        foreach ($this->messages as $message) {
            list($text, $level, $category, $timestamp) = $message;
            if (!is_string($text)) {
                // exceptions may not be serializable if in the call stack somewhere is a Closure
                if ($text instanceof \Throwable || $text instanceof \Exception) {
                    $text = (string) $text;
                } else {
                    $text = VarDumper::export($text);
                }
            }

            $url = isset(Yii::$app->request->url)? Yii::$app->request->url : '';

            // only Error Exceptions
            if($category == 'application') continue;

            if ($command->bindValues([
                    ':level' => $level,
                    ':category' => $category,
                    ':log_time' => $timestamp,
                    ':prefix' => $this->getMessagePrefix($message),
                    ':message' => $text,
                    ':url' => $url,
                ])->execute() > 0) {
                continue;
            }
            throw new LogRuntimeException('Unable to export log through database!');
        }
    }
}