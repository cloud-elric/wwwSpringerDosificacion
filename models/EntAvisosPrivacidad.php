<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_avisos_privacidad".
 *
 * @property string $id_aviso
 * @property string $txt_aviso
 * @property string $b_habilitado
 *
 * @property RelPacienteAviso[] $relPacienteAvisos
 */
class EntAvisosPrivacidad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_avisos_privacidad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_aviso'], 'required'],
            [['txt_aviso'], 'string'],
            [['b_habilitado'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_aviso' => 'Id Aviso',
            'txt_aviso' => 'Txt Aviso',
            'b_habilitado' => 'B Habilitado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelPacienteAvisos()
    {
        return $this->hasMany(RelPacienteAviso::className(), ['id_aviso' => 'id_aviso']);
    }
}
