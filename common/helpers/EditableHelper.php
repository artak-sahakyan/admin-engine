<?php
namespace common\helpers;

use yii\db\ActiveRecord;
use kartik\editable\Editable;
use yii\helpers\{ 
    ArrayHelper, 
    Url, 
    BaseArrayHelper 
};

class EditableHelper extends BaseArrayHelper
{
	const URL_ACTION = 'editable';
    const AS_POPOVER = true;
    const ADMIN_USER = 'admin-user';
    const ADMIN = 'Admin';

	/**
     * Создает Editable виджет для редактирования текстового поля
     * @param \yii\db\ActiveRecord $data
     * @param string $attribute
     * @return string
     */
    public static function text(ActiveRecord $data, string $attribute)
    {
        $ownerClassName = self::getOwnerClassName($data);

    	$url = Url::to([
             '/'.strtolower($ownerClassName) . '/' . self::URL_ACTION,
             'id' => $data->id
        ]);
    	$header = $data->getAttributeLabel(mb_strtolower($attribute));

        return Editable::widget([
	        'name'			=> $ownerClassName . '[' . $attribute . ']', 
	        'asPopover' 	=> self::AS_POPOVER,
	        'value' 		=> $data->$attribute,
	        'header' 		=> $header,
	        'formOptions' 	=> [
	            'action' => $url,
	        ],
	        'size'=>'sm'
	    ]);
    }

    /**
     * Создает Editable виджет типа выпадающего меню, для редактирования связи hasOne
     * @param \yii\db\ActiveRecord $data
     * @param string $attribute
     * @param string $relationName
     * @param array $dataArray
     * @return string
     */
    public static function dropdown(ActiveRecord $data, string $attribute, string $relationName, array $dataArray)
    {
        $relationAttribute = null;
        if(stripos($relationName, '.') !== false) {
           $parts = explode('.', $relationName);
            $relationName = $parts[0];
            $relationAttribute = $parts[1];
        }

    	$ownerClassName = $data->formName();
    	$url = Url::to([self::getUrl($ownerClassName),
                            'id' => $data->id,
                            'relationName' => $relationName,
                            'relationAttribute' => $relationAttribute
                        ]);
        $header = $data->getAttributeLabel(mb_strtolower($attribute));
    	$value = ($data->$relationName) ? $data->$relationName->id : null;

    	return Editable::widget([
            'name'			=> $ownerClassName . '[' . $attribute . ']',
            'value' 		=> $value,
            'asPopover' 	=> self::AS_POPOVER,
            'header' 		=> $header,
            'inputType' 	=> Editable::INPUT_DROPDOWN_LIST,
            'data' 			=> $dataArray,
            'options' 		=> ['class'=>'form-control', 'prompt'=>'Не выбрано'],
            'formOptions'   => [
                'action'    => $url,
            ],
            'displayValueConfig'=> $dataArray,
        ]);
    }

    /**
     * Создает Editable виджет типа переключателя
     * @param \yii\db\ActiveRecord $data
     * @param string $attribute
     * @param array $dataArray
     * @return string
     */
    public static function checkbox(ActiveRecord $data, string $attribute, array $dataArray)
    {
        $ownerClassName = self::getOwnerClassName($data);

        $url = Url::to([self::getUrl($ownerClassName),
                            'id' => $data->id,
                            'returnedValues' => json_encode($dataArray)
                        ]);

        $header = $data->getAttributeLabel(mb_strtolower($attribute));
        $value = $data->$attribute;

        return Editable::widget([
            'name'          => $ownerClassName . '[' . $attribute . ']',
            'value'         => $value,
            'asPopover'     => self::AS_POPOVER,
            'header'        => $header,
            'inputType'     => Editable::INPUT_DROPDOWN_LIST,
            'data'          => $dataArray,
            'options'       => ['class'=>'form-control'],
            'formOptions'   => [
                'action'    => $url,
            ],
            'displayValueConfig'=> $dataArray,
        ]);
    }

    public static function select2(ActiveRecord $data, string $relationName, string $className, array $dataArray)
    {
        $ownerClassName = $data->formName();
        $url = Url::to([self::getUrl($ownerClassName),
                            'id' => $data->id,
                            'relationName' => $relationName,
                            'mode' => 'multiple',
                            'className' => $className
                        ]);
        $header = $data->getAttributeLabel(mb_strtolower($relationName));

        $values = ArrayHelper::map($data->$relationName, 'id', 'name');
        $value = implode(', ', $values);
        
        return Editable::widget([
            'name'          => $ownerClassName . '[' . $relationName . ']',
            'value'         => $value,
            'asPopover'     => self::AS_POPOVER,
            'header'        => $header,
            'data'          => $values,
            'inputType'     => Editable::INPUT_SELECT2,
            'options'       => [
                'data'    => $dataArray,
                'class'   =>'form-control', 
                'options' => ['multiple' => true],
            ],
            'formOptions'   => [
                'action'    => $url,
            ],
        ]);
    }

    /**
     * Формирует url для ajax запроса
     * @param string $className
     * @return string
     */
    private function getUrl(string $className)
    {
        return strtolower($className) . '/' . self::URL_ACTION;
    }

    /**
     * Если состоит из двух слов добавляет - между ними для корректного создания url
     * @param object $data
     * @return string
     */
    private static function getOwnerClassName($data) {
        $ownerClassName = $data->formName();
        if($ownerClassName == self::ADMIN) return self::ADMIN_USER;
        $words = array_filter(preg_split('/(?=[A-Z])/',$ownerClassName));
        return (count($words) > 1) ? join('-', $words) : $ownerClassName;
    }
}
