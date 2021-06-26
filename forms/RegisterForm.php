<?php

declare(strict_types=1);

namespace app\forms;

use yii\base\Model;

class RegisterForm extends Model
{
    public string $email = '';
    public string $password = '';


    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
        ];
    }

}
