<?php

declare(strict_types=1);

namespace app\controllers\api;

use app\forms\RegisterForm;
use app\forms\UpdateForm;
use app\models\User;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UnprocessableEntityHttpException;

class UserController extends ActiveController
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

    public function behaviors(): array
    {
        return [
            'bearerAuth' => [
                'class' => HttpBearerAuth::class,
            ],
        ];
    }

    /**
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionUpdate(int $id): Response
    {
        $user = Yii::$app->user;
        if ($user->id !== $id) {
            throw new AccessDeniedException();
        }
        $form = new UpdateForm();
        $form->attributes = Yii::$app->getRequest()->getBodyParams();
        if ($form->validate() === false) {
            throw new UnprocessableEntityHttpException($user->errors);
        }
        if ($form->password !== '') {
            $form->password = Yii::$app->getSecurity()->generatePasswordHash($form->password);
        }
        $user = User::findOne(['id' => $user->id]);
        if ($user === null) {
            throw new NotFoundHttpException();
        }

        $user->setAttributes((array)$form);

        if ($user->validate(['email', 'password', 'first_name', 'last_name', 'phone'], false) === false) {
            throw new UnprocessableEntityHttpException('invalid data');
        }

        $user->save();
        return $this->asJson($user);
    }

    public function actionIndex(int $id): Response
    {
        $user = Yii::$app->user;
        if ($user->id !== $id) {
            throw new AccessDeniedException();
        }
        $user = User::findOne(['id' => $user->id]);

        return $this->asJson($user);
    }
}