<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_dosis".
 *
 * @property string $id_dosis
 * @property string $id_tratamiento
 * @property double $num_peso
 * @property double $num_estatura
 * @property string $txt_token
 * @property string $fch_creacion
 * @property string $fch_proxima_visita
 *
 * @property EntTratamiento $idTratamiento
 */
class EntDosis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_dosis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tratamiento', 'num_peso', 'num_estatura', 'txt_token'], 'required'],
            [['id_tratamiento'], 'integer'],
            [['num_peso', 'num_estatura'], 'number'],
            [['fch_creacion', 'fch_proxima_visita'], 'safe'],
            [['txt_token'], 'string', 'max' => 50],
            [['txt_token'], 'unique'],
            [['id_tratamiento'], 'exist', 'skipOnError' => true, 'targetClass' => EntTratamiento::className(), 'targetAttribute' => ['id_tratamiento' => 'id_tratamiento']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_dosis' => 'Id Dosis',
            'id_tratamiento' => 'Id Tratamiento',
            'num_peso' => 'Num Peso',
            'num_estatura' => 'Num Estatura',
            'txt_token' => 'Txt Token',
            'fch_creacion' => 'Fch Creacion',
            'fch_proxima_visita' => 'Fch Proxima Visita',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTratamiento()
    {
        return $this->hasOne(EntTratamiento::className(), ['id_tratamiento' => 'id_tratamiento']);
    }
}
