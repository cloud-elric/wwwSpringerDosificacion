<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_dosis".
 *
 * @property string $id_dosis
 * @property string $id_dosis_cliente
 * @property string $id_tratamiento
 * @property string $id_tratamiento_cliente
 * @property string $id_presentacion
 * @property string $txt_token
 * @property double $num_peso
 * @property double $num_dosis_sugerida
 * @property double $num_dosis_objetivo
 * @property double $num_dosis_objetivo_cal
 * @property double $num_dosis_acumulada
 * @property string $num_dosis_diaria
 * @property double $num_dosis_redondeada
 * @property string $num_tiempo_tratamiento
 * @property string $num_dias_tratamiento
 * @property string $num_meses
 * @property string $fch_creacion
 * @property string $fch_proxima_visita
 *
 * @property CatPresentacionMedicamentos $idPresentacion
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
            [['id_tratamiento', 'id_presentacion', 'txt_token', 'num_peso', 'num_dosis_sugerida', 'num_dosis_objetivo', 'num_dosis_objetivo_cal', 'num_dosis_acumulada', 'num_dosis_diaria', 'num_dosis_redondeada', 'num_tiempo_tratamiento', 'num_dias_tratamiento', 'num_meses'], 'required'],
            [['id_dosis_cliente', 'id_tratamiento', 'id_tratamiento_cliente', 'id_presentacion', 'num_dosis_diaria', 'num_tiempo_tratamiento', 'num_dias_tratamiento', 'num_meses'], 'integer'],
            [['num_peso', 'num_dosis_sugerida', 'num_dosis_objetivo', 'num_dosis_objetivo_cal', 'num_dosis_acumulada', 'num_dosis_redondeada'], 'number'],
            [['fch_creacion', 'fch_proxima_visita'], 'safe'],
            [['txt_token'], 'string', 'max' => 70],
            [['txt_token'], 'unique'],
            [['id_presentacion'], 'exist', 'skipOnError' => true, 'targetClass' => CatPresentacionMedicamentos::className(), 'targetAttribute' => ['id_presentacion' => 'id_presentacion']],
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
            'id_dosis_cliente' => 'Id Dosis Cliente',
            'id_tratamiento' => 'Id Tratamiento',
            'id_tratamiento_cliente' => 'Id Tratamiento Cliente',
            'id_presentacion' => 'Id Presentacion',
            'txt_token' => 'Txt Token',
            'num_peso' => 'Num Peso',
            'num_dosis_sugerida' => 'Num Dosis Sugerida',
            'num_dosis_objetivo' => 'Num Dosis Objetivo',
            'num_dosis_objetivo_cal' => 'Num Dosis Objetivo Cal',
            'num_dosis_acumulada' => 'Num Dosis Acumulada',
            'num_dosis_diaria' => 'Num Dosis Diaria',
            'num_dosis_redondeada' => 'Num Dosis Redondeada',
            'num_tiempo_tratamiento' => 'Num Tiempo Tratamiento',
            'num_dias_tratamiento' => 'Num Dias Tratamiento',
            'num_meses' => 'Num Meses',
            'fch_creacion' => 'Fch Creacion',
            'fch_proxima_visita' => 'Fch Proxima Visita',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPresentacion()
    {
        return $this->hasOne(CatPresentacionMedicamentos::className(), ['id_presentacion' => 'id_presentacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTratamiento()
    {
        return $this->hasOne(EntTratamiento::className(), ['id_tratamiento' => 'id_tratamiento']);
    }
}
