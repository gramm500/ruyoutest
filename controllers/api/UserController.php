<?php

declare(strict_types=1);

namespace app\controllers\api;

use app\forms\RegisterForm;
use app\models\User;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = User::class;

    public function actions(): array
    {
        $actions = parent::actions();
        unset($actions['update']);
        unset($actions['index']);
        return $actions;
    }

    public function actionUpdate(): void
    {
        dd(1);
    }

    public function actionIndex()
    {

    }

    public function checkAccess($action, $model = null, $params = [])
    {
        // check if the user can access $action and $model
        // throw ForbiddenHttpException if access should be denied
        if ($action === 'update' || $action === 'delete') {
            if ($model->author_id !== \Yii::$app->user->id)
                throw new \yii\web\ForbiddenHttpException(sprintf('You can only %s articles that you\'ve created.', $action));
        }
    }
}