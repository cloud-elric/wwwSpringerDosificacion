<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_claves".
 *
 * @property string $id_clave
 * @property string $txt_clave
 * @property string $b_usado
 */
class CatClaves extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cat_claves';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_clave'], 'required'],
            [['b_usado'], 'integer'],
            [['txt_clave'], 'string', 'max' => 6],
            [['txt_clave'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_clave' => 'Id Clave',
            'txt_clave' => 'Txt Clave',
            'b_usado' => 'B Usado',
        ];
    }
}
