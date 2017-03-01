<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $name;
    public $email;
    public $password;
    public $country;
    public $birthday;

    const AVAILABLE_AGE_FOR_SNG = 18;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'match', 'pattern' => '/^([a-z0-9]{3,6}+([_\.\-]{1}[a-z0-9]+)*){1}([@]){1}([a-z0-9]+([_\-]{1}[a-z0-9]+)*)+(([\.]{1}[a-z]{2,6}((?!ru|ua).)){0,3}){1}$/'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['country', 'required'],

            ['birthday', 'required'],
            ['birthday', 'validDate'],
            ['birthday', 'checkAge', 'when' => function($model) {
                return in_array($model->country, [203, 'Ukraine', 173, 'Russian Federation']);
            }],

        ];
    }

    public function checkAge($attribute)
    {
        $dateModel = new \DateTime($this->$attribute);
        $age = ($dateModel->diff(new \DateTime()));
        if ((int)$age->format('%Y') <= self::AVAILABLE_AGE_FOR_SNG) {
            $this->addError($attribute, 'When mom stops you give money to the buns, come to us.');
        }
    }

    public function validDate($attribute)
    {
        $attrTimestamp = (new \DateTime($this->$attribute))->getTimestamp();
        $currentTimestamp = (new \DateTime())->getTimestamp();

        if($attrTimestamp > $currentTimestamp){
            $this->addError($attribute, 'DOB Must be valid.');
        }
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->email;
        $user->email = $this->email;
        $user->ip = \Yii::$app->request->getUserIP();
        $user->birthday = $this->birthday;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }
}
