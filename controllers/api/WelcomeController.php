<?php

declare(strict_types = 1);

namespace app\controllers\api;

use yii\rest\Controller;

class WelcomeController extends Controller
{
    public function actionIndex(): \yii\web\Response
    {
        return $this->asJson('welcome');
    }
}