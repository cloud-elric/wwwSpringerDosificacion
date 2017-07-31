<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_dosis".
 *
 * @property string $id_dosis
 * @property string $id_tratamiento
 * @property string $id_presentacion
 * @property double $num_peso
 * @property double $num_estatura
 * @property double $num_dosis_sugerida
 * @property double $num_dosis_acumulada
 * @property string $num_dosis_diaria
 * @property string $num_tiempo_tratamiento
 * @property string $num_dias_tratamiento
 * @property string $txt_token
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
            [['id_tratamiento', 'id_presentacion', 'num_peso', 'num_dosis_sugerida', 'num_dosis_acumulada', 'num_dosis_diaria', 'num_tiempo_tratamiento', 'num_dias_tratamiento', 'txt_token'], 'required'],
            [['id_tratamiento', 'id_presentacion', 'num_dosis_diaria', 'num_tiempo_tratamiento', 'num_dias_tratamiento'], 'integer'],
            [['num_peso', 'num_estatura', 'num_dosis_sugerida', 'num_dosis_acumulada'], 'number'],
            [['fch_creacion', 'fch_proxima_visita'], 'safe'],
            [['txt_token'], 'string', 'max' => 50],
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
            'id_tratamiento' => 'Id Tratamiento',
            'id_presentacion' => 'Id Presentacion',
            'num_peso' => 'Num Peso',
            'num_estatura' => 'Num Estatura',
            'num_dosis_sugerida' => 'Num Dosis Sugerida',
            'num_dosis_acumulada' => 'Num Dosis Acumulada',
            'num_dosis_diaria' => 'Num Dosis Diaria',
            'num_tiempo_tratamiento' => 'Num Tiempo Tratamiento',
            'num_dias_tratamiento' => 'Num Dias Tratamiento',
            'txt_token' => 'Txt Token',
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
