<?php
/**
 * Created by PhpStorm.
 * User: TrickTrick
 * Date: 28-Feb-17
 * Time: 16:51
 */

namespace frontend\widget;

use yii\base\Widget;

class DatalistWidget extends Widget
{
    public $options;

    public function init()
    {
        parent::init();
        if ($this->options === null) {
            $this->options = [];
        }
    }

    public function run()
    {
        return '<input type="text" name="test">123</text>';
    }

    /**
        <label> Enter your favorite movies:<br/>
        <input type="text" name="Card[movies]" list="movies"/>
        <datalist id="movies">

        <label> or select one from the list:
        <select name="Card[movies]">
        <option value="Star Wars">
        <option value="The Godfather">
        <option value="Goodfellas">
        </select>
        </label>
        </datalist>
        </label>
     */
}