<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_tratamiento".
 *
 * @property string $id_tratamiento
 * @property string $id_paciente
 * @property string $id_doctor
 * @property string $id_presentacion
 * @property string $id_tratamiento_cliente
 * @property string $txt_nombre_tratamiento
 * @property double $num_peso
 * @property double $num_dosis_sugerida
 * @property double $num_dosis_acumulada
 * @property string $num_dosis_diaria
 * @property string $num_tiempo_tratamiento
 * @property string $num_dias_tratamiento
 * @property string $fch_ultima_visita
 * @property string $b_habilitado
 *
 * @property EntDosis[] $entDoses
 * @property EntDoctores $idDoctor
 * @property EntPacientes $idPaciente
 * @property CatPresentacionMedicamentos $idPresentacion
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
            [['id_paciente', 'id_doctor', 'id_presentacion', 'id_tratamiento_cliente', 'num_dosis_diaria', 'num_tiempo_tratamiento', 'num_dias_tratamiento', 'b_habilitado'], 'integer'],
            [['num_peso', 'num_dosis_sugerida', 'num_dosis_acumulada'], 'number'],
            [['fch_ultima_visita'], 'safe'],
            [['txt_nombre_tratamiento'], 'string', 'max' => 500],
            [['id_doctor'], 'exist', 'skipOnError' => true, 'targetClass' => EntDoctores::className(), 'targetAttribute' => ['id_doctor' => 'id_doctor']],
            [['id_paciente'], 'exist', 'skipOnError' => true, 'targetClass' => EntPacientes::className(), 'targetAttribute' => ['id_paciente' => 'id_paciente']],
            [['id_presentacion'], 'exist', 'skipOnError' => true, 'targetClass' => CatPresentacionMedicamentos::className(), 'targetAttribute' => ['id_presentacion' => 'id_presentacion']],
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
            'id_presentacion' => 'Id Presentacion',
            'id_tratamiento_cliente' => 'Id Tratamiento Cliente',
            'txt_nombre_tratamiento' => 'Txt Nombre Tratamiento',
            'num_peso' => 'Num Peso',
            'num_estatura' => 'Num Estatura',
            'num_dosis_sugerida' => 'Num Dosis Sugerida',
            'num_dosis_acumulada' => 'Num Dosis Acumulada',
            'num_dosis_diaria' => 'Num Dosis Diaria',
            'num_tiempo_tratamiento' => 'Num Tiempo Tratamiento',
            'num_dias_tratamiento' => 'Num Dias Tratamiento',
            'fch_ultima_visita' => 'Fch Ultima Visita',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPresentacion()
    {
        return $this->hasOne(CatPresentacionMedicamentos::className(), ['id_presentacion' => 'id_presentacion']);
    }
}
