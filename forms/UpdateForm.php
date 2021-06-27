<?php

declare(strict_types=1);

namespace app\forms;

use yii\base\Model;

class UpdateForm extends Model
{
    public string $email = '';
    public string $password = '';
    public string $first_name = '';
    public string $last_name = '';
    public string $phone = '';


    public function rules(): array
    {
        return [
            [['email', 'password', 'first_name', 'last_name', 'phone'], 'string'],
            ['email', 'email'],
        ];
    }

}