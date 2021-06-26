<?php

declare(strict_types=1);

namespace app\controllers\api;

use app\forms\RegisterForm;
use app\models\User;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\rest\ActiveController;

class AuthController extends ActiveController
{
    public $modelClass = User::class;

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function actionRegister()
    {
        $user = new User();
        $user->attributes = \Yii::$app->getRequest()->getBodyParams();
        if ($user->validate() === false) {
            return $user->errors;
        }
        $user->load(\Yii::$app->getRequest()->getBodyParams());
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
        $user->save();

        return $this->asJson(['token: ' => $user->login()]);
    }
}