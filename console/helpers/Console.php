<?php

namespace console\helpers;


use console\helpers\logstorage\AbstractLogStorage;

class Console extends \yii\helpers\Console
{
    public static $command;

    public static $_progressStart;
    public static $_progressWidth;
    public static $_progressPrefix;
    public static $_progressEta;
    public static $_progressEtaLastDone = 0;
    public static $_progressEtaLastUpdate;

    /**
     * @var AbstractLogStorage
     */
    protected static $storage;

    /**
     * Console constructor.
     * @param AbstractLogStorage $storage
     * @example $logCatalog = \Yii::getAlias('@siteConsole')."/runtime/";
     * @example $console = new Console(new LogStorageFile($logCatalog));
     * @example $model = new LogStorageDB;
     * @example $console = new Console(new LogStorageDB($model));
     */
    public function __construct(AbstractLogStorage $storage)
    {
        self::$storage = $storage;
    }

    public function createLog($command=null)
    {
        self::$command = ($command) ? $this->normalizeCommand(substr(strrchr($command, '\\'), 1)) : 'console';

        self::$storage->init(self::$command);
    }

    /**
     * @param $success done|fail. fail is default
     */
    public function exitLog($success) {
        sleep(5);
        self::$storage->final($success);
    }

    /**
     * Write out in storage and output in default stdout.
     * @param null $string
     * @return bool|int
     */
    public static function output($string = '')
    {
        self::$storage->write($string);
        return static::stdout($string . PHP_EOL);
    }

    public function normalizeCommand($command = null)
    {
        $commandArray = array_filter(preg_split('/(?=[A-Z])/', ($command) ? $command : $this->command));
        array_pop($commandArray);
        $command = (count($commandArray) > 1) ? join('-', $commandArray) : null;
        return trim(mb_strtolower($command));
    }

    public static function startProgress($done, $total, $prefix = '', $width = null)
    {
        self::$_progressStart = time();
        self::$_progressWidth = $width;
        self::$_progressPrefix = $prefix;
        self::$_progressEta = null;
        self::$_progressEtaLastDone = 0;
        self::$_progressEtaLastUpdate = time();

        static::updateProgress($done, $total);
    }

    public static function updateProgress($done, $total, $prefix = null)
    {
        $prefix = '';

        $percent = ($total == 0) ? 1 : $done / $total;
        $info = sprintf('%d%% (%d/%d)', $percent * 100, $done, $total);
        self::setETA($done, $total);
        $info .= self::$_progressEta === null ? ' ETA: n/a' : sprintf(' ETA: %d sec.', self::$_progressEta);

        self::output($prefix . $info);
        self::$storage->afterWrite([
            'percent' => $percent * 100,
            'done' => $done,
            'total' => $total,
        ]);
        flush();
    }

    private static function getProgressbarWidth($prefix)
    {
        $width = self::$_progressWidth;

        if ($width === false) {
            return 0;
        }

        $screenSize = static::getScreenSize(true);
        if ($screenSize === false && $width < 1) {
            return 0;
        }

        if ($width === null) {
            $width = $screenSize[0];
        } elseif ($width > 0 && $width < 1) {
            $width = floor($screenSize[0] * $width);
        }

        $width -= static::ansiStrlen($prefix);

        return $width;
    }

    public static function setETA($done, $total)
    {
        if ($done > $total || $done == 0) {
            self::$_progressEta = null;
            self::$_progressEtaLastUpdate = time();
            return;
        }

        if ($done < $total && (time() - self::$_progressEtaLastUpdate > 1 && $done > self::$_progressEtaLastDone)) {
            $rate = (time() - (self::$_progressEtaLastUpdate ?: self::$_progressStart)) / ($done - self::$_progressEtaLastDone);
            self::$_progressEta = $rate * ($total - $done);
            self::$_progressEtaLastUpdate = time();
            self::$_progressEtaLastDone = $done;
        }
    }
}