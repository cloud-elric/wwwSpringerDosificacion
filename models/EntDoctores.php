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
            [['txt_nombre', 'txt_apellido_paterno', 'txt_email', 'txt_password'], 'required', 'message'=>'Campo requerido'],
            [['b_habilitado'], 'integer'],
            [['txt_nombre', 'txt_apellido_paterno', 'txt_password'], 'string', 'max' => 50],
            [['txt_email'], 'unique', 'message'=>'Email ya registrado'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_doctor' => 'Id',
            'txt_nombre' => 'Nombre',
            'txt_apellido_paterno' => 'Apellido Paterno',
            'txt_email' => 'Email',
            'txt_password' => 'Password',
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

    public function getIdClave()
   {
       return $this->hasOne(CatClaves::className(), ['id_clave' => 'id_clave']);
   }

   /**
    * @return \yii\db\ActiveQuery
    */
   public function getEntPacientes()
   {
       return $this->hasMany(EntPacientes::className(), ['id_doctor' => 'id_doctor']);
   }

   /**
    * @return \yii\db\ActiveQuery
    */
   public function getEntTratamientos()
   {
       return $this->hasMany(EntTratamiento::className(), ['id_doctor' => 'id_doctor']);
   }
}
