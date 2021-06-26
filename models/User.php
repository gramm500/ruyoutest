<?php

declare(strict_types=1);

namespace app\models;

use Symfony\Component\Finder\Exception\AccessDeniedException;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{

    private const  EXPIRE_TIME = 604800; // valid a week
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d h:i:s'),
            ]
        ];
    }

    public function rules(): array
    {
        return [
            [['email', 'password'], 'safe'],

        ];
    }

    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = self::find()->where(['token' => $token])->one;
        if ($user === null) {
            return null;
        }
        if ($user->expires_at < time()) {
            throw new AccessDeniedException('access token expired');
        } else {
            return $user;
        }
    }

    /**
     * @throws Exception
     */
    public function login(): string
    {
        $access_token = $this->generateAccessToken();
        $this->expires_at = time() + static::EXPIRE_TIME;
        $this->token = $access_token;
        $this->save();
        Yii::$app->user->login($this, static::EXPIRE_TIME);
        return $access_token;
    }

    /**
     * @throws Exception
     */
    public function generateAccessToken(): string
    {
        $this->token = Yii::$app->security->generateRandomString();
        return $this->token;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
