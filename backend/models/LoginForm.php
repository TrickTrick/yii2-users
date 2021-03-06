<?php
namespace backend\models;

use common\models\User;
use common\models\UserLog;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user;

    const SCENARIO_BACKEND_LOGIN = 'backend-login';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required', 'on' => [self::SCENARIO_BACKEND_LOGIN]],

            ['email', 'checkAccess', 'on' => [self::SCENARIO_BACKEND_LOGIN]],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean', 'on' => [self::SCENARIO_BACKEND_LOGIN]],
            // password is validated by validatePassword()
            ['password', 'validatePassword', 'on' => [self::SCENARIO_BACKEND_LOGIN]],
        ];
    }

    /**
     * Validates the user access.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function checkAccess($attribute)
    {
        $user = $this->getUser();
        if(isset($user) && $user->cannotGetAccess()){
            $this->addError($attribute, 'Incorrect username or password.');
        }
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            if(UserLog::add($this->getUser()) && User::canILogIn($this->getUser())){
                return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            }
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
