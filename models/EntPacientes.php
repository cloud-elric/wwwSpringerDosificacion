<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_pacientes".
 *
 * @property string $id_paciente
 * @property string $txt_nombre
 * @property string $txt_apellido_paterno
 * @property string $txt_apellido_materno
 * @property string $txt_email
 * @property string $txt_telefono_contacto
 * @property string $fch_nacimiento
 * @property string $b_habilitado
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
            [['txt_nombre', 'txt_apellido_paterno'], 'required'],
            [['fch_nacimiento'], 'safe'],
            [['b_habilitado'], 'integer'],
            [['txt_nombre', 'txt_apellido_paterno', 'txt_apellido_materno', 'txt_email', 'txt_telefono_contacto'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_paciente' => 'Paciente',
            'txt_nombre' => 'Nombre',
            'txt_apellido_paterno' => 'Apellido Paterno',
            'txt_apellido_materno' => 'Apellido Materno',
            'txt_email' => 'Email',
            'txt_telefono_contacto' => 'Telefono ',
            'fch_nacimiento' => 'Fecha Nacimiento',
            'b_habilitado' => 'B Habilitado',
        ];
    }
}
