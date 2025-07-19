<?php

namespace app\models;

use yii\db\ActiveRecord;

class UserRefreshToken extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_refresh_tokens}}';
    }
}
?>