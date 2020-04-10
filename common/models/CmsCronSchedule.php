<?php

namespace common\models;

use Yii;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;
use yii\validators\Validator;

/**
 * This is the model class for table "cms_cron_schedules".
 *
 * @property int $id
 * @property string $command
 * @property string $schedule
 * @property int $is_active
 * @property string $params
 * @property string $description
 */
class CmsCronSchedule extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cms_cron_schedules}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['command', 'schedule'], 'required'],
            [['is_active'], 'integer'],
            [['description'], 'string'],
            [['command', 'params'], 'string', 'max' => 255],
            [['schedule'], 'string', 'max' => 50],
            ['schedule', 'validateTime']
        ];
    }

    public function validateTime($attribute, $params, $validator)
    {

        $attributes = explode(' ', trim($this->$attribute));
        if(count($attributes) == 5) {
            $props = ['min', 'hour', 'day', 'month', 'weekDay'];
            $combined = array_combine($props, $attributes);

            $model = DynamicModel::validateData($combined, [
                [['min', 'hour'], 'match', 'pattern' => '/^[0-9\*\/\,\-]+$/s'],
                ['month', 'match', 'pattern' => '/^[A-Z0-9\*\/\,\-]+$/s'],
                ['day', 'match', 'pattern' => '/^[0-9\*\/\,\-\?LW]+$/s'],
                ['weekDay', 'match', 'pattern' => '/^[A-Z0-6\*\/\,\-L#]+$/s'],
            ]);

            if ($model->hasErrors()) {
                $this->addError($attribute, 'Wrong param in crone timer');
            }
        } else {
            $this->addError($attribute, 'Wrong length for cron');
        }

    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'command' => 'Команда',
            'schedule' => 'Расписание',
            'is_active' => 'Активность',
            'params' => 'Параметры',
            'description' => 'Описание'
        ];
    }

    public function getOptions() {
        $allOptions = self::find()->select(['command', 'description'])->asArray()->orderBy('id DESC')->all();
        $options = [];

        if($allOptions) {
            foreach ($allOptions as $option) {
                $description = trim($option['description']);
                $options[$option['command']] = $description ? $description : $option['command'];
            }
        }
        return ['' => 'Виберите комманду'] + $options;
    }

    public function getConsoleCommandsFiles() {
        $controllers = Yii::getAlias('@console') . '/controllers';
        $files = array_map(function($file) { return  basename($file, '.php'); }, glob($controllers . '/*'));
        return ['' => 'Виберите комманду'] + array_combine($files,$files);
    }

    public function normalizeCommand($command = null) {
        $commandArray = array_filter(preg_split('/(?=[A-Z])/', ($command) ? $command : $this->command));
        array_pop($commandArray);
        $command = (count($commandArray) > 1) ? join('-', $commandArray) : null;
        return trim(mb_strtolower($command));
    }

    public function getJobs($currents, $oldCommand)
    {
        $jobs = [];
        $jobs['jobs'] = [];
        foreach ($currents as $k => $current) {
            if (stripos($current, $oldCommand) !== false) {
                unset($currents[$k]);
            }
        }

        if ($currents) {
            foreach ($currents as $current) {
                if($current) {
                    (stripos($current, '#') !== false) ? $jobs['headLines'][] = $current : $jobs['jobs'][] = ['line' => $current];
                }
            }
        }
        return $jobs;
    }

    public function getLine($noComment = true) {
        return (!$noComment ? '# ' : '') . $this->schedule . ' ' . self::getCommandPath() . ' ' . $this->normalizeCommand($this->command) . ' ' .$this->params;
    }

    public static function getCommandPath() {
        return 'php ' . \Yii::getAlias('@sitePath') . '/yii';
    }

    public function getLogPath()
    {
        return \Yii::getAlias('@siteConsole')."/runtime/" . (($this->command) ? $this->normalizeCommand($this->command) : 'console') . ".log";
    }

}
