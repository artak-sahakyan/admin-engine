<?php

namespace common\helpers;


use Yii;
use yii\helpers\FileHelper;

class FilesHelper
{
    public static function getLastRelatedArticlesFullUpdateTime($setTime = false) {

        $folder = Yii::getAlias('@siteFrontend') . '/runtime/lastFullUpdateRelated';

        if(!is_dir($folder))  mkdir($folder, 0777, true);

        $filePath = $folder.'/time.txt';
        $f = fopen($filePath, 'w');
        if($setTime) fputs($f, time());

        chmod($filePath, 0666);

        if(file_exists($filePath) && is_readable($filePath)) {
            $time = (int)file_get_contents($filePath);
            return $time ? date('Y-m-d H:i:s', $time) : false;
        }

        return false;
    }

    public static function createImportArticleLogFile() {
        $folder = Yii::getAlias('@siteFrontend') . '/runtime/importedArticles';
        if(!is_dir($folder))  mkdir($folder, 0777, true);
        $logFilePath = $folder.'/data.log';

        if(file_exists($logFilePath)) {
            unlink($logFilePath);
        }


        fopen($logFilePath, 'a+');
        return $logFilePath;
    }

    public static function checkIsRunningProcess($command) {
        return  file_exists(\Yii::getAlias('@siteConsole')."/runtime/" .  self::normalizeCommand($command) . ".log");
    }

    public static function normalizeCommand($command) {
        $commandArray = array_filter(preg_split('/(?=[A-Z])/', $command));
        array_pop($commandArray);
        $command = (count($commandArray) > 1) ? join('-', $commandArray) : null;
        return trim(mb_strtolower($command));
    }


    public static function createErrorLogFile() {
        $folder = Yii::getAlias('@siteFrontend') . '/runtime/errors';
        if(!is_dir($folder))  mkdir($folder, 0777, true);
        $logFilePath = $folder . '/' . date('Y-m-d-H') .'.log';

        if(file_exists($logFilePath)) {
            unlink($logFilePath);
        }

        return @fopen($logFilePath, 'a+');
    }

    public static function writeErrorLogs($f, $log, $countLogs)
    {
        static $first = 0;
        $logText = (!$first) ? 'Всего ' . $countLogs . ' ошибок' . "\n" : '<------------   ------------->';
        $logText .= 'Log Time ' . date('Y-m-d H:i:s', $log->log_time) . ' ErrorLevel : ' . $log->level . "\n";
        $logText .= 'Log Category ' . $log->category . ' Url ' . $log->url . "\n";
        $logText .= '----- Message ----' . "\n";
        $logText .= $log->message;
        fwrite($f, $logText . "\n\n\n");

        $first++;

        if ($first == $countLogs) {
            @fclose($f);
            chmod($f, 0666);
        }
    }

    /**
     * every mount first day delete folder files before create new
     * @return int
     */
    public static function deleteErrorLogs()
    {
        $firstDayOfMonth = date('d', strtotime('first day of this month'));
        $currentDay = date('d');

        if ($firstDayOfMonth == $currentDay) {
            $folder = Yii::getAlias('@siteFrontend') . '/runtime/errors';
            if ($files = glob($folder . '/*')) {
                foreach ($files as $file) {
                    @unlink($file);
                }
            }
        }
        return 1;
    }
  
    public static function getConfigs($key=null) {
        static $configs = [];

        $configsPath = Yii::getAlias('@siteFrontend') . '/web/params.ini';
        if (empty($configs) && file_exists($configsPath)) {
            $configs = parse_ini_file($configsPath, true);
        }

        return (!empty($configs[$key])) ? $configs[$key] : $configs;
    }

    public static function convertArrayAsIniStr(array $arr)
    {
        return array_reduce(array_keys($arr), function($str, $sectionName) use ($arr) {
            $sub = $arr[$sectionName];
            return $str . "[$sectionName]" . PHP_EOL .
                array_reduce(array_keys($sub), function($str, $key) use($sub) {
                    return $str . $key . '=' . $sub[$key] . PHP_EOL;
                }) . PHP_EOL;
        });
    }

    public static function setConfigs($key, $params) {
        $configsPath =  Yii::getAlias('@siteFrontend') . '/web/params.ini';
        if(file_exists($configsPath)) {
            $configs = parse_ini_file($configsPath, true);
            if($key && !empty($configs[$key]) && $params) {
                $configs[$key] = $params;
                $configs = self::convertArrayAsIniStr($configs);
                @file_put_contents($configsPath, $configs);
                return true;
            }
        }
        return false;
    }

    public static function deleteDirectoryByPath($dirPath) {
        $folders = FileHelper::findDirectories($dirPath, ['recursive' =>false]);
        if($folders) {
            foreach ($folders as $folder) {
                FileHelper::removeDirectory($folder);
            }
        }
        return true;
    }

    public static function currentDomainBackendControllers() {
        $folder = Yii::getAlias('@siteBackend') . '/controllers';

        if ($files = glob($folder . '/*')) {
            foreach ($files as $k => $file) {
                $files[$k] = basename($file, '.php');
            }
        }
        return $files;
    }

    public static function convertControllerNamesToMap(Array $controllers, Array $commonParams) {
        $map = [];

        if(empty($commonParams['backendNamespace'])) die('Add backendNamespace key to common params');

        foreach ($controllers as $controllerName) {
            $onlyName = lcfirst(str_replace('Controller', '', $controllerName));
            $map[$onlyName] = [
                'class' => $commonParams['backendNamespace'] . $controllerName,
                'viewPath' => '@siteBackend/views/' . $onlyName
            ];
        }

        return $map;
    }

    public static function getBackendControllerMap($commonParams, $commonParamsPath) {
        $controllerMap = [];
        if($controllers = self::currentDomainBackendControllers()) {
            $controllerMap =  self::convertControllerNamesToMap($controllers, $commonParams);
            foreach (array_keys($controllerMap) as $name) {
                $commonParams['backendControllers'][$name] = true;
            }
        } else {
            $commonParams['backendControllers'] = [];
        }

        $data = '<?php' . "\r\n" . 'return ' .  var_export($commonParams, true) . ';';
        file_put_contents($commonParamsPath, $data);

        return $controllerMap;

    }



}