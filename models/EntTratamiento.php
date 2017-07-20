<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_tratamiento".
 *
 * @property string $id_tratamiento
 * @property string $id_paciente
 * @property string $id_doctor
 * @property string $txt_nombre_tratamiento
 * @property string $b_habilitado
 *
 * @property EntDosis[] $entDoses
 * @property EntDoctores $idDoctor
 * @property EntPacientes $idPaciente
 */
class EntTratamiento extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_tratamiento';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_paciente', 'id_doctor', 'txt_nombre_tratamiento'], 'required'],
            [['id_paciente', 'id_doctor', 'b_habilitado'], 'integer'],
            [['txt_nombre_tratamiento'], 'string', 'max' => 500],
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
            'id_tratamiento' => 'Id Tratamiento',
            'id_paciente' => 'Id Paciente',
            'id_doctor' => 'Id Doctor',
            'txt_nombre_tratamiento' => 'Txt Nombre Tratamiento',
            'b_habilitado' => 'B Habilitado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntDoses()
    {
        return $this->hasMany(EntDosis::className(), ['id_tratamiento' => 'id_tratamiento']);
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
