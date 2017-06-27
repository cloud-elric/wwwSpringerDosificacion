<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_dosis".
 *
 * @property string $id_dosis
 * @property string $id_doctor
 * @property string $id_paciente
 * @property double $num_peso
 * @property double $num_estatura
 * @property string $fch_creacion
 * @property string $fch_proxima_visita
 *
 * @property EntDoctores $idDoctor
 * @property EntPacientes $idPaciente
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
            [['id_doctor', 'id_paciente', 'num_peso', 'num_estatura'], 'required'],
            [['id_doctor', 'id_paciente'], 'integer'],
            [['num_peso', 'num_estatura'], 'number'],
            [['fch_creacion', 'fch_proxima_visita'], 'safe'],
            [['id_doctor'], 'exist', 'skipOnError' => true, 'targetClass' => EntDoctores::className(), 'targetAttribute' => ['id_doctor' => 'id_doctor']],
            [['id_paciente'], 'exist', 'skipOnError' => true, 'targetClass' => EntPacientes::className(), 'targetAttribute' => ['id_paciente' => 'id_paciente']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_dosis' => 'Id Dosis',
            'id_doctor' => 'Id Doctor',
            'id_paciente' => 'Id Paciente',
            'num_peso' => 'Num Peso',
            'num_estatura' => 'Num Estatura',
            'fch_creacion' => 'Fch Creacion',
            'fch_proxima_visita' => 'Fch Proxima Visita',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdDoctor()
    {
        return $this->hasOne(EntDoctores::className(), ['id_doctor' => 'id_doctor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPaciente()
    {
        return $this->hasOne(EntPacientes::className(), ['id_paciente' => 'id_paciente']);
    }
}
