<?php
/**
 * Created by PhpStorm.
 * User: TrickTrick
 * Date: 28-Feb-17
 * Time: 16:51
 */

namespace frontend\widget;

use yii\bootstrap\Html;
use yii\helpers\StringHelper;
use yii\widgets\InputWidget;

class DatalistWidget extends InputWidget
{
    public $options;
    public $placeholder;

    public function init()
    {
        parent::init();
        if ($this->options === null) {
            $this->options = [];
        }
    }

    public function run()
    {
        if ($this->hasModel()) {
            $name = StringHelper::basename(get_class($this->model)) . '[' . $this->attribute . ']';
            $result = Html::input('text', $name, null, ['list' => $this->attribute, 'class' => 'form-control', 'placeholder' => $this->placeholder]);
            $result .= '<datalist id="' . $this->attribute . '">';
            $result .= '<select name="' . $name . '">';
            foreach ($this->options as $id => $option) {
                $result .= '<option value="' . $option . '">' . $id . '</option>';
            }
            $result .= '</select>';
            $result .= '</datalist>';
        }
        echo $result;
    }
}