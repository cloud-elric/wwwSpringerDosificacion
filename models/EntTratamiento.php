<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_tratamiento".
 *
 * @property string $id_tratamiento
 * @property string $id_tratamiento_cliente
 * @property string $id_paciente
 * @property string $id_paciente_cliente
 * @property string $id_doctor
 * @property string $id_presentacion
 * @property string $txt_nombre_tratamiento
 * @property string $txt_token
 * @property double $num_peso
 * @property double $num_dosis_sugerida
 * @property double $num_dosis_objetivo
 * @property double $num_dosis_objetivo_cal
 * @property double $num_dosis_acumulada
 * @property double $num_dosis_diaria
 * @property double $num_dosis_redondeada
 * @property string $num_dias_tratamiento
 * @property string $num_meses
 * @property string $fch_ultima_visita
 * @property string $fch_inicio_tratamiento
 * @property string $fch_fin_tratamiento
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
            [['id_tratamiento_cliente', 'id_paciente', 'id_paciente_cliente', 'id_doctor', 'id_presentacion', 'num_dias_tratamiento', 'num_meses', 'b_habilitado'], 'integer'],
            [['id_paciente', 'id_doctor', 'txt_nombre_tratamiento', 'txt_token'], 'required'],
            [['num_peso', 'num_dosis_sugerida', 'num_dosis_objetivo', 'num_dosis_objetivo_cal', 'num_dosis_acumulada', 'num_dosis_diaria', 'num_dosis_redondeada'], 'number'],
            [['fch_ultima_visita', 'fch_inicio_tratamiento', 'fch_fin_tratamiento'], 'safe'],
            [['txt_nombre_tratamiento'], 'string', 'max' => 500],
            [['txt_token'], 'string', 'max' => 70],
            [['txt_token'], 'unique'],
            [['id_doctor'], 'exist', 'skipOnError' => true, 'targetClass' => EntDoctores::className(), 'targetAttribute' => ['id_doctor' => 'id_doctor']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tratamiento' => 'Id Tratamiento',
            'id_tratamiento_cliente' => 'Id Tratamiento Cliente',
            'id_paciente' => 'Id Paciente',
            'id_paciente_cliente' => 'Id Paciente Cliente',
            'id_doctor' => 'Id Doctor',
            'id_presentacion' => 'Id Presentacion',
            'txt_nombre_tratamiento' => 'Txt Nombre Tratamiento',
            'txt_token' => 'Txt Token',
            'num_peso' => 'Num Peso',
            'num_dosis_sugerida' => 'Num Dosis Sugerida',
            'num_dosis_objetivo' => 'Num Dosis Objetivo',
            'num_dosis_objetivo_cal' => 'Num Dosis Objetivo Cal',
            'num_dosis_acumulada' => 'Num Dosis Acumulada',
            'num_dosis_diaria' => 'Num Dosis Diaria',
            'num_dosis_redondeada' => 'Num Dosis Redondeada',
            'num_dias_tratamiento' => 'Num Dias Tratamiento',
            'num_meses' => 'Num Meses',
            'fch_ultima_visita' => 'Fch Ultima Visita',
            'fch_inicio_tratamiento' => 'Fch Inicio Tratamiento',
            'fch_fin_tratamiento' => 'Fch Fin Tratamiento',
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
