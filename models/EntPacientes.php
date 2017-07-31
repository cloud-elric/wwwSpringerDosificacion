<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_pacientes".
 *
 * @property string $id_paciente
 * @property string $id_doctor
 * @property string $txt_nombre
 * @property string $txt_apellido_paterno
 * @property string $txt_apellido_materno
 * @property string $txt_email
 * @property string $txt_telefono_contacto
 * @property integer $num_edad
 * @property string $txt_sexo
 * @property string $txt_token
 * @property string $b_habilitado
 *
 * @property EntDoctores $idDoctor
 * @property EntTratamiento[] $entTratamientos
 * @property RelPacienteAviso $relPacienteAviso
 */
class EntPacientes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_pacientes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_doctor', 'txt_nombre', 'txt_apellido_paterno', 'num_edad', 'txt_sexo', 'txt_token'], 'required'],
            [['id_doctor', 'num_edad', 'b_habilitado'], 'integer'],
            [['txt_nombre', 'txt_apellido_paterno', 'txt_apellido_materno'], 'string', 'max' => 100],
            [['txt_email', 'txt_telefono_contacto', 'txt_sexo', 'txt_token'], 'string', 'max' => 50],
            [['txt_token'], 'unique'],
            [['txt_email'], 'unique'],
            [['id_doctor'], 'exist', 'skipOnError' => true, 'targetClass' => EntDoctores::className(), 'targetAttribute' => ['id_doctor' => 'id_doctor']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_paciente' => 'Id Paciente',
            'id_doctor' => 'Id Doctor',
            'txt_nombre' => 'Txt Nombre',
            'txt_apellido_paterno' => 'Txt Apellido Paterno',
            'txt_apellido_materno' => 'Txt Apellido Materno',
            'txt_email' => 'Txt Email',
            'txt_telefono_contacto' => 'Txt Telefono Contacto',
            'num_edad' => 'Num Edad',
            'txt_sexo' => 'Txt Sexo',
            'txt_token' => 'Txt Token',
            'b_habilitado' => 'B Habilitado',
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
    public function getEntTratamientos()
    {
        return $this->hasMany(EntTratamiento::className(), ['id_paciente' => 'id_paciente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelPacienteAviso()
    {
        return $this->hasOne(RelPacienteAviso::className(), ['id_paciente' => 'id_paciente']);
    }
}
