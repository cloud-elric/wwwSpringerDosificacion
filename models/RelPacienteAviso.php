<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rel_paciente_aviso".
 *
 * @property string $id_paciente
 * @property string $id_aviso
 * @property string $b_aceptado
 *
 * @property EntAvisosPrivacidad $idAviso
 * @property EntPacientes $idPaciente
 */
class RelPacienteAviso extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rel_paciente_aviso';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_paciente', 'id_aviso'], 'required'],
            [['id_paciente', 'id_aviso', 'b_aceptado'], 'integer'],
            [['id_aviso'], 'exist', 'skipOnError' => true, 'targetClass' => EntAvisosPrivacidad::className(), 'targetAttribute' => ['id_aviso' => 'id_aviso']],
            [['id_paciente'], 'exist', 'skipOnError' => true, 'targetClass' => EntPacientes::className(), 'targetAttribute' => ['id_paciente' => 'id_paciente']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_paciente' => 'Id Paciente',
            'id_aviso' => 'Id Aviso',
            'b_aceptado' => 'B Aceptado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdAviso()
    {
        return $this->hasOne(EntAvisosPrivacidad::className(), ['id_aviso' => 'id_aviso']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPaciente()
    {
        return $this->hasOne(EntPacientes::className(), ['id_paciente' => 'id_paciente']);
    }
}
