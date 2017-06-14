<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_doctores".
 *
 * @property string $id_doctor
 * @property string $txt_nombre
 * @property string $txt_apellido_paterno
 * @property string $txt_email
 * @property string $txt_password
 * @property string $b_habilitado
 */
class EntDoctores extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_doctores';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_nombre', 'txt_apellido_paterno', 'txt_email', 'txt_password'], 'required'],
            [['b_habilitado'], 'integer'],
            [['txt_nombre', 'txt_apellido_paterno', 'txt_email', 'txt_password'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_doctor' => 'Id Doctor',
            'txt_nombre' => 'Txt Nombre',
            'txt_apellido_paterno' => 'Txt Apellido Paterno',
            'txt_email' => 'Txt Email',
            'txt_password' => 'Txt Password',
            'b_habilitado' => 'B Habilitado',
        ];
    }

    /**
     * Busca al doctor en la base de datos
     * @param $usuario
     * @param $pass
     * @return EntDoctores
     */
    public static function getDoctor($usuario=null, $pass=null){
        $doctor = EntDoctores::find()->where(['txt_email'=>$usuario, 'txt_password'=>$pass])->one();

        return $doctor;
    }
}
