<?php
namespace frontend\models;

use backend\helpers\UserPermissions;
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
    public $role;

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
            ['email', 'match', 'pattern' => '/^([a-z0-9]{3,}+([_\.\-]{1}[a-z0-9]+)*){1}([@]){1}([a-z0-9]+([_\-]{1}[a-z0-9]+)*)+(([\.]{1}[a-z]{2,6}){0,3}){1}$/',
                'message' => 'Enter a valid email.'],
            ['email', 'isAdequate', 'when' => function($model) {
                $mailParts = explode('@', $model->email);
                return preg_match('(\.ru|\.ua)', $mailParts[1]);
            }],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['country', 'required'],

            ['birthday', 'required'],
            ['birthday', 'validDate'],

            ['role', 'required'],
        ];
    }
    /**
     * email depend of birthday validation
     */
    public function isAdequate($attribute)
    {
        $birthday = new \DateTime($this->birthday);
        $currentDateTime = new \DateTime();
        if((int)$birthday->diff($currentDateTime)->format('%Y') < self::AVAILABLE_AGE_FOR_SNG){
            $this->addError($attribute, 'When mom stops you give money to the buns, come to us.');
        }
    }

    /**
     * birthday validation
     */
    public function validDate($attribute)
    {
        $attrTimestamp = (new \DateTime($this->$attribute))->getTimestamp();
        $currentTimestamp = (new \DateTime())->getTimestamp();

        if($attrTimestamp > $currentTimestamp){
            $this->addError($attribute, 'Day Of Birthday Must be valid.');
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
        $user->role = $this->role;
        $user->ip = \Yii::$app->request->getUserIP();
        $user->birthday = $this->birthday;
        $user->country = $this->country;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if($user->save()){

            /** add role */
            if($this->canISetRole()){
                $auth = \Yii::$app->authManager;
                $userRole = $auth->getRole(UserPermissions::giveRole($this->role));
                $auth->assign($userRole, $user->id);
            }

            return $user;
        }
        return null;
    }

    protected function canISetRole()
    {
        return in_array($this->role, [User::ROLE_MANAGER, User::ROLE_ADMINISTRATOR]);
    }
}
