<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EntPacientes;

/**
 * EntPacientesSearch represents the model behind the search form about `app\models\EntPacientes`.
 */
class EntPacientesSearch extends EntPacientes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_paciente', 'b_habilitado'], 'integer'],
            [['txt_nombre', 'txt_apellido_paterno', 'txt_apellido_materno', 'txt_email', 'txt_telefono_contacto', 'fch_nacimiento'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = EntPacientes::find()->where(['b_habilitado'=>1]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_paciente' => $this->id_paciente,
            'fch_nacimiento' => $this->fch_nacimiento,
            'b_habilitado' => $this->b_habilitado,
        ]);

        $query->andFilterWhere(['like', 'txt_nombre', $this->txt_nombre])
            ->andFilterWhere(['like', 'txt_apellido_paterno', $this->txt_apellido_paterno])
            ->andFilterWhere(['like', 'txt_apellido_materno', $this->txt_apellido_materno])
            ->andFilterWhere(['like', 'txt_email', $this->txt_email])
            ->andFilterWhere(['like', 'txt_telefono_contacto', $this->txt_telefono_contacto]);

        return $dataProvider;
    }
}
