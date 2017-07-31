<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_presentacion_medicamentos".
 *
 * @property string $id_presentacion
 * @property string $txt_nombre
 * @property string $txt_descripcion
 * @property string $num_capsulas
 * @property string $num_dosis_capsula
 * @property string $url_imagen
 *
 * @property EntDosis[] $entDoses
 * @property EntTratamiento[] $entTratamientos
 */
class CatPresentacionMedicamentos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cat_presentacion_medicamentos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_nombre', 'txt_descripcion', 'num_capsulas', 'num_dosis_capsula', 'url_imagen'], 'required'],
            [['num_capsulas', 'num_dosis_capsula'], 'integer'],
            [['txt_nombre', 'txt_descripcion', 'url_imagen'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_presentacion' => 'Id Presentacion',
            'txt_nombre' => 'Txt Nombre',
            'txt_descripcion' => 'Txt Descripcion',
            'num_capsulas' => 'Num Capsulas',
            'num_dosis_capsula' => 'Num Dosis Capsula',
            'url_imagen' => 'Url Imagen',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntDoses()
    {
        return $this->hasMany(EntDosis::className(), ['id_presentacion' => 'id_presentacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntTratamientos()
    {
        return $this->hasMany(EntTratamiento::className(), ['id_presentacion' => 'id_presentacion']);
    }
}
