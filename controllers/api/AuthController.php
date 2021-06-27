<?php

declare(strict_types=1);

namespace app\controllers\api;

use app\forms\LoginForm;
use app\models\User;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\Response;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

class AuthController extends ActiveController
{
    public $modelClass = User::class;

    public function actions(): array
    {
        $actions = parent::actions();
        unset(
            $actions['index'],
            $actions['create'],
            $actions['update'],
            $actions['delete'],
        );
        return $actions;
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function actionRegister(): Response
    {
        $user = new User();
        $user->attributes = \Yii::$app->getRequest()->getBodyParams();
        if ($user->validate() === false) {
            throw new UnprocessableEntityHttpException($user->errors);
        }
        $user->load(\Yii::$app->getRequest()->getBodyParams());
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
        $user->save();

        return $this->asJson(['token: ' => $user->login()]);
    }

    /**
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     * @throws UnprocessableEntityHttpException
     */
    public function actionLogin(): Response
    {
        $form = new LoginForm();
        $form->attributes = Yii::$app->getRequest()->getBodyParams();
        if ($form->validate() === false) {
            throw new UnprocessableEntityHttpException($form->errors);
        }
        $user = User::find()->where(['email' => $form->email])->one();
        if ($user === null) {
            throw new NotFoundHttpException('user with this email doesn\'t exists in the database');
        }
        $passwordValidation = Yii::$app->getSecurity()->validatePassword($form->password, $user->password);
        if ($passwordValidation === false) {
            throw new AccessDeniedException('the credentials doesn\'t match');
        }

        return $this->asJson(['token: ' => $user->login()]);
    }
}